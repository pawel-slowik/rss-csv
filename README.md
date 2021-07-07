![Build Status][build-badge]
[![Coverage][coverage-badge]][coverage-url]

[build-badge]: https://github.com/pawel-slowik/rss-csv/workflows/tests/badge.svg
[coverage-badge]: https://codecov.io/gh/pawel-slowik/rss-csv/branch/master/graph/badge.svg
[coverage-url]: https://codecov.io/gh/pawel-slowik/rss-csv

Download a RSS/Atom feed and save its contents into a CSV file.

Note: this is a job interview task, not a complete solution.

## Installation

	composer install

## Usage

List of commands:

	php ./src/console.php list

Help for a command:

	php ./src/console.php command --help

## TODO

- Refactoring - move more code from RssClient to separate classes, because protected
  methods are problematic when it comes to testing.
- More unit tests.
