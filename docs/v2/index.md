# Repman 2.x documentation

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

Repman 2.x runs on Symfony 5.4.

## Pages

- [Installation](installation.md)
- [Configuration](configuration.md)
- [Usage](usage.md)
- [API integration](api-integration.md)
- [Docker](docker.md)
- [Testing](testing.md)
- [Upgrading from 1.x to 2.0](upgrading.md)

The documentation of the previous versions is published at [repman.io/docs](https://repman.io/docs/).
