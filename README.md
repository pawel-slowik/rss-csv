Download a RSS/Atom feed and save its contents into a CSV file.

Note: this is a job interview task, not a complete solution.

## Installation

	composer install

## Usage

List of commands:

	php ./src/console.php list

Help for a command:

	php ./src/console.php command --help

## Components used

| component               | reason |
| ----------------------- | ------ |
| koriym/php-skeleton     | high on the [list of packages with the Skeleton tag](https://packagist.org/search/?tags=Skeleton) |
| symfony/console         | de facto standard for PHP console apps |
| zendframework/zend-feed | transparently handles various non-standard extensions |
| zendframework/zend-http | default for zend-feed (Guzzle requires an adapter) |
| ezyang/htmlpurifier     | `strip_tags` is not safe |
| league/csv              | comes up first when googling for `php composer csv` |

## TODO

- Refactoring - move more code from RssReader to separate classes, because protected
  methods are problematic when it comes to testing.
- More unit tests.
