#!/bin/sh
set -e
############################################################################################################################
#                                        Helper script to help us run tests.
############################################################################################################################
#
# It will:
#   1. Build containers with support for the appropriate PHP and ALPINE versions, as specified by the environment variables
#       _PHP_VERSION_ and _ALPINE_VERSION_
#   2. Install dependencies. Deliberately we do not update dependencies when switching php versions, as we will be
#         running tests using different versions than the ones that will actually be installed
#   3. Run Tests
#
# Usage:
#       PHP_VERSION=7.4 ALPINE_VERSION=3.12 ./.divido/build/run_tests.sh
#
############################################################################################################################

if [[ -z "${PHP_VERSION}" ]]; then
  1>&2 echo 'ERROR:: PHP_VERSION env var not set'; exit 1;
fi

if [[ -z "${ALPINE_VERSION}" ]]; then
  1>&2 echo 'ERROR:: ALPINE_VERSION env var not set'; exit 2;
fi

DOCKER_COMPOSE() {
    PHP_VERSION=${PHP_VERSION} ALPINE_VERSION=${ALPINE_VERSION} docker-compose -f ./.divido/development/console.yml $@
}

echo ">>> Building Container for PHP ${PHP_VERSION} on ALPINE ${ALPINE_VERSION}"
DOCKER_COMPOSE build --pull

echo ">>>  Install dependencies"
DOCKER_COMPOSE run --rm console composer install

echo ">>>  Run Tests"
DOCKER_COMPOSE run --rm console ./vendor/bin/phpunit tests/ --stop-on-failure --coverage-clover=tmp/coverage.clover.xml --coverage-text=tmp/coverage.out