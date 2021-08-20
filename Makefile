export MINIMUM_TEST_COVERAGE:=71
export PHP_VERSION=7.4
export ALPINE_VERSION=3.14

default: ci-clean ci-fmt ci-analyze ci-test ci-check-coverage

composer-install:
	CMD='composer install' \
		docker-compose -f ./.divido/development/console.yml up --build --force-recreate --abort-on-container-exit

composer-update:
	CMD='composer update' \
		docker-compose -f ./.divido/development/console.yml up --build --force-recreate --abort-on-container-exit

ci-clean:
	docker-compose -f ./.divido/development/console.yml rm --force -v

ci-fmt:
	docker run --rm -v $(PWD):/project -e FOLDERS=lib,tests divido/devtools:php-fmt

ci-test: composer-install
	PHP_VERSION=7.3 ALPINE_VERSION=3.12 ./.divido/build/run_tests.sh
	PHP_VERSION=7.4 ./.divido/build/run_tests.sh
	make ci-clean


ci-analyze: composer-install
	docker-compose -f ./.divido/development/console.yml run --rm console vendor/bin/phpstan analyse

ci-check-coverage:

ci-build:

ci-push:
