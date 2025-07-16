![Build Status][build-badge]
[![Coverage][coverage-badge]][coverage-url]

[build-badge]: https://github.com/pawel-slowik/rss-csv/workflows/tests/badge.svg
[coverage-badge]: https://codecov.io/gh/pawel-slowik/rss-csv/branch/master/graph/badge.svg
[coverage-url]: https://codecov.io/gh/pawel-slowik/rss-csv

Download a RSS/Atom feed and save its contents into a CSV file.

Note: this is a job interview task, not a complete solution.

## Installation

	docker compose build dev-cli
	docker compose run --rm dev-cli composer install

## Usage

List of commands:

	docker compose run --rm dev-cli ./src/console.php list

Help for a command:

	docker compose run --rm dev-cli ./src/console.php command --help
