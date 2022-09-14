export MINIMUM_TEST_COVERAGE:=71
export PHP_VERSION=7.4
export ALPINE_VERSION=3.14
export ECR_REGISTRY:=625359548668.dkr.ecr.eu-west-1.amazonaws.com

# To allow downloading of devtools, please run the following command:
# aws sso login --profile=divido-general

ifndef CI
	IGNORE:=$(shell aws ecr get-login-password --region eu-west-1 --profile divido-general \
		| docker login --username AWS --password-stdin ${ECR_REGISTRY})
endif

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
	docker run --rm -v $(PWD):/project \
		-e FOLDERS=lib,tests \
		-e ALLOW_RISKY=yes \
		${ECR_REGISTRY}/devtools:php-fmt

ci-test: composer-install
	PHP_VERSION=7.3 ALPINE_VERSION=3.12 ./.divido/build/run_tests.sh
	PHP_VERSION=7.4 ./.divido/build/run_tests.sh
	make ci-clean

ci-analyze:
	docker run --rm \
		-v $(PWD):/project \
		-w /project \
		ghcr.io/phpstan/phpstan analyse -c phpstan.neon

ci-check-coverage:

ci-build:

ci-push:
