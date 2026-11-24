# Testing

The Composer scripts of the project:

- `composer phpunit:unit`, `composer phpunit:integration` and `composer phpunit:functional` run the single PHPUnit suites (integration and functional tests need PostgreSQL, see `DATABASE_URL`)
- `composer phpunit` runs all of them
- `composer check-cs` checks the coding style (PHP-CS-Fixer) and `composer fix-cs` fixes it
- `composer phpstan` runs PHPStan at level `max`
- `composer tests` runs the whole quality pipeline

The GitHub Actions workflow runs the test suites on PHP 8.4, 8.5 and 8.6 with PostgreSQL 17.
The coding style check runs on PHP 8.4 only, because PHP-CS-Fixer does not support PHP 8.6 syntax yet.
