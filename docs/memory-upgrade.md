# Memoria upgrade PHP

## Esecuzione 2026-11-24 — TARGET_PHP 8.6
- Modalità: CURRENT di fatto (il vincolo `php` è `^7.4.2 || ^8.0` e consente già 8.6). Versioni verificate: 7.4.33 (baseline), 8.4.28, 8.5.13, 8.6.0.
- Fork check: `offline-agency/repman` è un fork di `repman-io/repman`, identico a upstream (0 commit solo-fork). La regola `stop-if-upstream-supports` avrebbe fermato il lavoro; l'utente ha chiesto esplicitamente di procedere ("prepara i fix su un branch"). Il remote `upstream` è stato aggiunto.
- Gate formale (Step 2) non eseguito: nessun cambio di dipendenze. `composer.json`, `composer.lock` e `config.platform.php` (7.4.2) sono invariati.
- Verifica: Docker (`php:<ver>-cli-alpine` + PostgreSQL 17) con la stessa sequenza del CI. Harness (non nel repo): `scratchpad/dk/` (Dockerfile, run.sh, inner.sh).

### Problemi trovati e come sono stati risolti
1. Fixture `tests/Resources/buddy/p/buddy-works/repman.json` e `tests/Resources/fixtures/buddy/p/buddy-works/repman.json` finivano con `\n`: da PHP 8.3 `unserialize()` emette un warning sui dati extra. Rimosso il newline finale; regola in `.editorconfig`. Il codice di produzione usa `serialize()` ed è corretto.
2. `ComposerPackageSynchronizerTest::testSynchronizeError`: PHP 8.6 non mette più il percorso nel messaggio di `RecursiveDirectoryIterator::__construct()`. Regex resa tollerante.
3. Functional (`ProxyControllerTest`): le deprecazioni stampate su stdout (es. `spl_object_hash()` deprecata in 8.6, da `TraceableEventDispatcher` di Symfony 5.4) finivano nel buffer parsato come JSON. `display_errors=Off` in `phpunit.xml.dist`.
4. Le immagini ufficiali `php:*-cli-alpine` non hanno `mbstring` (richiesto da PHPUnit): aggiunto a `install-php-extensions` nel CI.
5. php-cs-fixer 3.9.3 (max PHP 8.1) e PHPStan 1.8.7 (fatal error su 8.4+) non girano su PHP recenti: nel CI vengono eseguiti solo sulla riga 7.4. Follow-up: aggiornare i due tool.

### Risultati (Docker, PostgreSQL 17)
| PHP | unit | integration | functional |
|---|---|---|---|
| 7.4.33 | 226 OK | 59 OK | 260 OK |
| 8.4.28 | 226 OK | 59 OK | 260 OK |
| 8.5.13 | 226 OK | 59 OK | 260 OK |
| 8.6.0 | 226 OK | 59 OK | 260 OK |

- Due test sono instabili al cambio di secondo (`PackageControllerTest::testFindPackage`, `UserOAuthTokenRefresherTest::testRefreshTokenIsTakenFromStorageNotFromTheCaller`): hanno fallito una volta sotto carico (run in parallelo) e sono passati al rerun. Non dipendono dalla versione di PHP.
- Deprecazioni vendor su 8.4–8.6: molte (PHPUnit 9.5, `composer/composer` 1.x, Symfony 5.4, aws-sdk, react, guzzle, prophecy). Non bloccanti; non risolvibili senza aggiornare le dipendenze.
- L'immagine `php:8.6-cli-alpine` è ZTS, mentre le altre sono NTS: Forge usa NTS.

### Non verificato / follow-up
- Sincronizzazione reale con GitHub/GitLab/Packagist e `composer/composer` 1.x su 8.6; prova su staging Forge con `APP_ENV=prod`, `APP_DEBUG=0`, `display_errors=Off` e le estensioni intl, pdo_pgsql, zip, mbstring.
- Aggiornare php-cs-fixer, PHPStan e PHPUnit (dev) per girare su 8.4+; Symfony 5.4 è a fine supporto.
- Proporre le stesse correzioni a `repman-io/repman`.
- Il CI non include 8.1–8.3 (non verificate).

### Branch
- `chore/php-8.6-upgrade` (locale, **non pushato**, nessuna PR aperta).
