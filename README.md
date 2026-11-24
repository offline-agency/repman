# Repman - PHP Repository Manager

[![Latest Release](https://img.shields.io/github/v/release/offline-agency/repman?sort=semver)](https://github.com/offline-agency/repman/releases)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.4-8892BF.svg)](https://php.net/)
[![Tests](https://github.com/offline-agency/repman/actions/workflows/tests.yaml/badge.svg?branch=master)](https://github.com/offline-agency/repman/actions/workflows/tests.yaml)
[![License](https://img.shields.io/github/license/offline-agency/repman)](LICENSE)
[![codecov](https://codecov.io/gh/repman-io/repman/branch/master/graph/badge.svg)](https://codecov.io/gh/repman-io/repman)
[![Hits-of-Code](https://hitsofcode.com/github/repman-io/repman)](https://hitsofcode.com/view/github/repman-io/repman)
[![Maintainability](https://api.codeclimate.com/v1/badges/23a93132c8273cabf9eb/maintainability)](https://codeclimate.com/github/repman-io/repman/maintainability)

**Repman** is a PHP repository manager. Main features:

- free and open source
- works as a proxy for **packagist.org** (speeds up your local builds)
- hosts your private packages
- allows to create individual access tokens
- supports private package import from **GitHub**, **GitLab** and **Bitbucket** with one click
- REST API
- security scanner (with e-mail reports)

## Requirements

- PHP >= 8.4 (8.4, 8.5 and 8.6 are tested in CI)
- PostgreSQL 11 - 17
- `var` dir must be writeable
- any web server

## Compatibility

| Version | PHP | Framework |
|---|---|---|
| 2.x | ^8.4 (8.4, 8.5, 8.6) | Symfony 5.4 |
| 1.x | ^7.4.1 | Symfony 5.4 |
| 0.x | ^7.4.1 | Symfony 5.1 |

## Documentation

- [Documentation index and versions](docs/README.md)
- [Documentation of the current version (2.x)](docs/v2/index.md)
- [Upgrading from 1.x to 2.0](docs/v2/upgrading.md)
- [https://repman.io/docs/](https://repman.io/docs/) (previous versions)

## Installation

- Docker: [docs/v2/docker.md](docs/v2/docker.md) and [docs/v2/installation.md](docs/v2/installation.md)
- Ansible: [docs/v2/installation.md](docs/v2/installation.md)
- Manual:

```bash
git clone git@github.com:repman-io/repman.git
cd repman
composer install
```

Then setup the database and the messenger transports, see [docs/v2/installation.md](docs/v2/installation.md).
Configuration (mailer, workers, GitLab, storage) is described in [docs/v2/configuration.md](docs/v2/configuration.md).

## Support

In case of any problems, you can use:

- Our documentation: [repman.io/docs](https://repman.io/docs/) - it is also open sourced [github.com/repman-io/repman-docs-pages](https://github.com/repman-io/repman-docs-pages)
- GitHub issue list: [github.com/repman-io/repman/issues](https://github.com/repman-io/repman/issues) - feel free to create a new issue there
- E-mail: contact [at] repman.io

## License

The Repman project is licensed under the terms of the [MIT](LICENSE).

However, Repman includes several third-party Open-Source libraries, which are licensed under their own respective Open-Source licenses.

#### Libraries or projects directly included in Repman

- Tabler: [MIT](https://github.com/tabler/tabler/blob/master/LICENSE)
- Feather: [MIT](https://github.com/feathericons/feather/blob/master/LICENSE)
- Lucide: License: [ISC](https://github.com/lucide-icons/lucide/blob/master/LICENSE)
- Postmark Transactional Email Templates: [MIT](https://github.com/wildbit/postmark-templates/blob/master/LICENSE)
- Libraries dynamically referenced via Composer: run `composer license` to get the latest licensing info about all dependencies.

---

made with ❤️ by [Buddy](https://buddy.works)
