# Installation

## Docker

[https://repman.io/docs/standalone/#docker-installation](https://repman.io/docs/standalone/#docker-installation)

See also [Docker](docker.md).

## Ansible

[https://repman.io/docs/standalone/#ansible-playbooks-installation](https://repman.io/docs/standalone/#ansible-playbooks-installation)

The `php` role installs the PHP version set in `php_version` (`8.4` by default).

## Manual

```bash
git clone git@github.com:repman-io/repman.git
cd repman
composer install
```

Setup database:

```
bin/console doctrine:migrations:migrate #for postgres
bin/console doctrine:schema:create #for sqlite init as migrations are only postgres-compatible
bin/console messenger:setup-transports
```
