# Memoria upgrade PHP

## Esecuzione 2026-11-24 (quarta) — TARGET_PHP 8.6, completata con le regole dell'utente
- **Esito: aggiornato** (gate sbloccato dalle regole dell'utente, vedi sotto). Branch `chore/php-8.6-upgrade`, PR #1 `offline-agency/repman` (stesso branch, titolo e descrizione portati in modalità LEGACY).
- **Modalità: LEGACY** (la matrice CI di `origin/master` arrivava a 8.0). SUPPORTED_PHP = 8.4, 8.5, 8.6; `php` `^8.4`, `config.platform.php` `8.4.0`, `composer.lock` risolto con PHP 8.4.
- **Regole dell'utente per questo run:** `docs/memory-upgrade.md` non conta come tree sporco; gli abbandonati non sono bloccanti (sensio → REPLACE con funzioni native; recaptcha → tenuto, supporta Symfony fino alla 7); le advisory non sono bloccanti (BUMP dove possibile, le altre come avvisi); Prophecy rimosso con il test riscritto con i mock PHPUnit; PR #1 sullo stesso branch.
- **Gate (2c) sul composer.json finale:** albero risolto su 8.4 ok · 8.5 ok · 8.6 ok (`--no-security-blocking`, per le advisory rimaste). Estensioni (ctype, curl, iconv, intl, json, pdo_pgsql, zip + quelle del lock): incluse in PHP, presenti nei binari Herd.
- **Framework target: Symfony 5.4**, trattenuto da `league/flysystem-bundle ^1` (richiede `symfony/dependency-injection` <= 5) e `league/flysystem-cached-adapter` (`psr/cache ^1`). Salire a 6.4/7.4 richiede Flysystem 3 (riscrivere lo storage e perdere il livello di cache). Symfony 8.0 non è installabile: tutte le release 8.0.x di framework-bundle, http-client, validator, yaml hanno advisory, e `excelwebzone/recaptcha-bundle` arriva solo alla 7.x.
- **Versione:** 1.4.0 (tag, antenato di `origin/master`; il fork non ha tag né release) → **2.0.0** (MAJOR: PHP minimo da 7.4 a 8.4). `Kernel::REPMAN_VERSION` valeva 1.3.4 anche nel tag 1.4.0: ora 2.0.0.
- **Rilascio:** `.github/workflows/release.yml` aggiunto (non c'era automazione). Al merge su `master`, se in `CHANGELOG.md` c'è una versione senza tag, crea il tag `2.0.0` (TAG_PREFIX vuoto) e la GitHub Release con la sezione come note. Dry-run: `WOULD RELEASE 2.0.0`, note identiche alla sezione 2.0.0, caso "tag già esistente" idempotente. YAML validato con js-yaml (actionlint non installato). Nessun tag né release creati da questa sessione (per provare l'idempotenza ho creato e subito cancellato un tag locale `2.0.0`, mai pushato).
- **Docs:** layout "none" → `docs/v2/` (index, installation, configuration, usage, api-integration, docker, testing, upgrading) + indice `docs/README.md`; `UPGRADE.md` aggiornato; nessun `.oa-docs.json`. README ridotto con tabella di compatibilità (2.x ^8.4/Symfony 5.4; 1.x ^7.4.1/Symfony 5.4; 0.x ^7.4.1/Symfony 5.1, dai `composer.json` dei tag 1.4.0 e 0.6.0).
- **QA:** php-cs-fixer (già presente, quindi niente Pint) 3.95 e PHPStan 1.12 livello `max`, già presente; nessuna voce di baseline aggiunta, 10 rimosse perché non più necessarie. PHPStan ok su 8.4, 8.5, 8.6. php-cs-fixer 3.95 non supporta la sintassi 8.6: in CI gira solo su 8.4 (eseguito in locale su 8.5).
- **Badge:** verificati con curl. Rimossi Docker Pulls ("rate limited by upstream service") e Uptime Robot (0%), già rimossi Buddy (403) e i badge Packagist (il pacchetto non è su Packagist). "Latest Release" mostra "no releases found" e il badge Tests "no status": si attivano al merge. Nessuna release creata per un tag esistente: il fork non ha tag.

### Passi completati (commit sul branch)
Prophecy → mock PHPUnit; `chore(php)`: php ^8.4 + lock rigenerato (pin 5.4.* dei componenti Symfony che Composer 2.11 + Flex 1.x non filtra); sostituzione di `sensio/framework-extra-bundle` (8 converter → `ArgumentValueResolverInterface` con priorità 150, 16 `@IsGranted` → `denyAccessUnlessGranted()`, `@Cache` → `setPrivate()`, `doctrine/annotations` esplicito); stile php-cs-fixer 3.95; messaggio "Too Many Requests" su GitLab 429 (omines/oauth2-gitlab 3.8 controlla prima il corpo); Twig/DBAL senza API deprecate (`compact_html`, `CoreExtension::convertDate`, `ArrayParameterType`); PHPStan max pulito; Composer 2.11 + CommonMark 2.10 (test sul path repository reso indipendente dal ramo git); CI (matrice 8.4/8.5/8.6, `validate --strict`); Dockerfile, Buddy e ruolo Ansible a 8.4; docs per major; changelog + versione; README; `release.yml`.

### Problemi incontrati
- Il mirror `repman.io` va in timeout/504 su `psr/http-factory-implementation`: lock generato in una cartella di scratch con Packagist e hash ricalcolato con `Composer\Package\Locker::getContentHash` (le URL di dist erano già tutte `api.github.com`).
- Composer 2.11 con Flex 1.x non applica `extra.symfony.require`: i componenti non elencati salivano a 6.x/8.0; fissati a `5.4.*` in `composer.json`.
- Con `--no-plugins` (gate) le stesse risoluzioni salgono di major: non usarlo per generare il lock.
- Il CI su 8.5/8.6 falliva in `ramsey/composer-install` per le deprecazioni di Composer 2.6.6 su stdout: file `.ini` con `error_reporting` senza `E_DEPRECATED`.

### Risultati (Docker, PostgreSQL 17, immagini `php:<v>-cli-alpine`)
8.4.28 · 8.5.13 · 8.6.0: unit 229 OK, integration 59 OK, functional 260 OK; `composer validate --strict`, PHPStan, lint Twig, migrazioni, schema validate, `composer audit`, warmup prod tutti ok. La riga 8.6 dell'immagine è ZTS (le altre NTS).

### Avvisi (advisory rimaste) e follow-up
- `league/flysystem` 1.x (con `flysystem-cached-adapter` e `flysystem-bundle` 1.x) e `guzzlehttp/guzzle` 6.x (imposto da `buddy-works/buddy-works-php-api` 1.4, ultima release): nessuna alternativa senza riscrivere lo storage / il client. `composer audit` col Composer 2.6.6 del CI non le segnala; Composer 2.11 sì.
- Vendor deprecations su 8.6: `sentry/sentry-symfony` 4.14, `guzzlehttp/promises`, `symfony/flex` 1.x, `symfony/error-handler` 5.4; `composer` 2.6.6 (strumento CI) molte.
- Ansible non eseguibile qui: ruolo `php` aggiornato a 8.4 (non verificato); `docker-compose.yml` usa ancora l'immagine `buddy/repman:1.3.4` (non toccata, è dell'upstream).
- Verifica su staging Forge con `APP_ENV=prod`, `APP_DEBUG=0`, `display_errors=Off`; sincronizzazioni reali con GitHub/GitLab/Packagist non provate.
- Valutare Flysystem 3 (sblocca Symfony 6.4/7.4 e chiude l'advisory), PHPStan 2 e php-cs-fixer con supporto 8.6, e proporre le correzioni a `repman-io/repman`.

---

## (superata) Esecuzione 2026-11-24 (seconda) — TARGET_PHP 8.6, prompt esteso (QA, versioning, docs, badge, release automatica)
- **Esito: BLOCCATO dal gate.** Nessun branch nuovo, nessun file del repo modificato, nessun push, nessuna release/tag. Questo file è l'unica modifica (non committata).
- **Modalità: LEGACY** (regola 1.6: la matrice CI di `origin/master` arrivava a PHP 8.0, sotto 8.4). SUPPORTED_PHP = 8.4, 8.5, 8.6; il vincolo diventerebbe `^8.4` e `config.platform.php` 8.4.0. La PR #1 già aperta tiene invece 7.4/8.0 (impostazione "CURRENT"): non coerente con LEGACY, lasciata invariata.
- **Fork:** `offline-agency/repman`, identico a `repman-io/repman` (0 commit solo-fork). Il fork-stop era già stato superato con un ok esplicito dell'utente in questa conversazione ("vai prepara i fix su un branch"); vale per questo repository.
- **Working tree non pulito:** modifica locale dell'utente a `docker-compose.yml` (porta 443 → 8443). Non toccata; il lavoro previsto sarebbe stato fatto in un `git worktree` separato.
- **Versionamento:** ultima versione stabile = tag `1.4.0` (antenato di `origin/master`, 39 commit prima; presente in locale da upstream, non nel fork, che non ha né tag né release). TAG_PREFIX vuoto. NEXT_VERSION previsto: `2.0.0` (MAJOR: PHP minimo da 7.4 a 8.4). `Kernel::REPMAN_VERSION` vale `1.3.4` anche nel tag 1.4.0: da portare a 2.0.0. Nessuna automazione di release esistente, nessun `.oa-docs.json`, `docs/` senza documentazione (solo memoria), `UPGRADE.md` presente nella root.
- **Framework target:** Symfony 5.4. Le prove con 6.4, 7.4 e 8.0 falliscono: `composer/composer ^1.10` richiede `symfony/console` <= 5, `sensio/framework-extra-bundle ^5.5` richiede `framework-bundle` <= 5, `doctrine/doctrine-bundle` in alcuni rami (8.0). Non è un blocco, ma il framework non può salire senza sostituire quei pacchetti.
- **Gate 2b (72 dipendenze dirette, metadati Packagist):** 70 OK, 2 OK ma abbandonate senza sostituto.
- **Gate 2c (scratch, platform 8.4.0 / 8.5.0 / 8.6.0, vincolo `^8.4`):** con il blocco advisory di Composer 2.11 (default) la risoluzione fallisce su tutte e tre; con `--no-security-blocking`: 8.4 ok, 8.5 ok, **8.6 ko**.
- **Gate 2d:** estensioni (ctype, curl, iconv, intl, json, pdo_pgsql, zip + quelle del lock) tutte incluse in PHP e presenti nei binari Herd 8.4, 8.5, 8.6.

### Bloccanti
1. **`phpspec/prophecy` (transitivo, dev) su PHP 8.6**, via `phpspec/prophecy-phpunit ^2.0`: l'ultima release, v1.26.1, richiede `php 8.2.* || 8.3.* || 8.4.* || 8.5.*`. Senza `prophecy-phpunit` l'albero si risolve su 8.6 (provato). Solo 1 file di test su 106 usa Prophecy. Alternativa: sostituire Prophecy con i mock di PHPUnit in quel test e rimuovere `phpspec/prophecy-phpunit`; oppure attendere una release di Prophecy che consenta 8.6.
2. **Abbandonati senza sostituto:** `sensio/framework-extra-bundle` (sostituito dalle funzioni di Symfony, non da un pacchetto) ed `excelwebzone/recaptcha-bundle`. Per la regola sono BLOCKER, anche se funzionano su 8.4–8.6 (test verdi in Docker).
3. **Advisory di sicurezza** (non di compatibilità PHP): Composer 2.11 esclude tutte le release di `composer/composer` 1.x, `guzzlehttp/guzzle` 6.x, `league/commonmark` 1.x e `league/flysystem` 1.x (e pacchetti che li richiedono: `flysystem-cached-adapter`, `flysystem-memory`, `flysystem-aws-s3-v3`). Il comando 2c "as written" fallisce per questo su tutte le versioni.

### Verifiche collaterali
- Il test su PHP 8.6 della prima esecuzione (PR #1) era valido a livello di runtime ma non contestava il vincolo: `config.platform.php` = 7.4.2 nasconde il tetto di Prophecy. Prophecy è solo dev (`--no-dev` in produzione non è toccato).
- Badge README attuali: 7 su 8 rispondono 200; il badge Buddy risponde 403 (token). Dalla PR non sono stati modificati.
- Un primo giro di risoluzioni è fallito per timeout del mirror `repo.repman.io` (6 job paralleli): rifatto sulla copia di scratch con Packagist diretto.

### Riesecuzione dello stesso prompt (2026-11-24, terza)
- Gate non rifatto (Step 0: riuso). Ricontrollato: `origin/master` = `upstream/master` = `d5eff3f` (invariati); `phpspec/prophecy` v1.26.1 ancora `php 8.2.* … 8.5.*` (8.6.0 escluso); `sensio/framework-extra-bundle` (abbandonato → Symfony) ed `excelwebzone/recaptcha-bundle` (abbandonato) invariati.
- Preflight 1.1: working tree non pulito (`docker-compose.yml` modificato dall'utente). Esito: fermato, nessuna modifica al repo oltre a questo file.

### Se si vuole sbloccare
- Decidere la sorte di `phpspec/prophecy-phpunit` (vedi punto 1), che da solo basta per 8.6.
- Decidere se i due pacchetti abbandonati e le advisory vanno accettati (esplicitamente) o sostituiti: è un lavoro a parte (Symfony 5.4 → 6.4+ richiede di togliere `composer/composer` 1.x e `sensio/framework-extra-bundle`, cioè riscrivere parti dell'app).
- La PR #1 resta valida come aggiornamento dei test/CI su 8.4–8.6, ma non rispetta LEGACY.

---

## Esecuzione 2026-11-24 — TARGET_PHP 8.6
- Modalità (valutazione di allora, poi corretta nell'esecuzione del 2026-11-24 sotto: la regola 1.6 dà LEGACY): CURRENT di fatto (il vincolo `php` è `^7.4.2 || ^8.0` e consente già 8.6). Versioni verificate: 7.4.33 (baseline), 8.4.28, 8.5.13, 8.6.0.
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
