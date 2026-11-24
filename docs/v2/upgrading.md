# Upgrading from 1.x to 2.0

- PHP 8.4 or later is required: PHP 7.4 to 8.3 are no longer supported. PHP 8.4, 8.5 and 8.6 are tested in CI.
  - the `Dockerfile` is now based on `php:8.4-fpm-alpine`, so rebuild your image
  - the Ansible role installs the PHP version set in `php_version` (`8.4` by default) and no longer installs the `php-json` package, because JSON is built into PHP 8
- run `composer install` and clear the cache (`bin/console cache:clear`)
- Symfony stays on `5.4`, so there is no Symfony upgrade guide to follow
- `sensio/framework-extra-bundle` (abandoned) was removed, so if you have made custom changes that use its annotations:
  - request converters (`ParamConverterInterface`) are now argument value resolvers: implement `Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface` and tag the service with `controller.argument_value_resolver` and a priority above `100`, as done in `config/services.yaml`
  - `@IsGranted("ROLE_NAME", subject="organization")` becomes `$this->denyAccessUnlessGranted('ROLE_NAME', $organization);` at the start of the action
  - `@Cache(public=false)` becomes `$response->setPrivate()`
- `doctrine/annotations` is now required explicitly (it used to be installed by the bundle above)
- `twig/twig` 3.9 or later is required: the deprecated `spaceless` filter was replaced by the `compact_html` filter and `twig_date_converter()` by `CoreExtension::convertDate()`
- no database migration was added
