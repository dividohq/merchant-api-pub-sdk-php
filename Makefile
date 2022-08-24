export MINIMUM_TEST_COVERAGE:=71
export PHP_VERSION=7.4
export ALPINE_VERSION=3.14
export ECR_REGISTRY:=625359548668.dkr.ecr.eu-west-1.amazonaws.com
export ECR_PASSWORD:=
ifndef CI
	LOGIN_OUTPUT := $(shell aws ecr get-login-password --region eu-west-1 --profile divido-general \
		| docker login --username AWS --password-stdin 625359548668.dkr.ecr.eu-west-1.amazonaws.com)
else
	ECR_PASSWORD := $(shell docker run --rm \
		-e AWS_ACCESS_KEY_ID=${ORG_AWS_ACCESS_KEY_ID} \
		-e AWS_SECRET_ACCESS_KEY=${ORG_AWS_SECRET_ACCESS_KEY} \
		-e AWS_DEFAULT_REGION=eu-west-1 \
		amazon/aws-cli ecr get-login-password --region eu-west-1)
endif
export SERVICE_NAME:=merchant-api-pub-sdk-php

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
	docker run --rm -v $(PWD):/project -e FOLDERS=lib,tests  ${ECR_REGISTRY}/devtools:php-fmt

ci-test: composer-install
	PHP_VERSION=7.3 ALPINE_VERSION=3.12 ./.divido/build/run_tests.sh
	PHP_VERSION=7.4 ./.divido/build/run_tests.sh
	make ci-clean


ci-analyze: composer-install
	docker  run --rm -e CHECKS="phpstan" -v $(PWD):/project ${ECR_REGISTRY}/devtools:php-analysis

ci-check-coverage:

ci-build:
	@docker build \
		--label "divido.com.container.version=${RELEASE_VERSION}" \
		--build-arg GITHUB_TOKEN=${GITHUB_TOKEN} \
		--target final \
		-t ${SERVICE_NAME}:${RELEASE_VERSION} \
		-f .divido/development/docker/console/Dockerfile \
		.
	docker tag ${SERVICE_NAME}:${RELEASE_VERSION} ${ECR_REGISTRY}/${SERVICE_NAME}:${RELEASE_VERSION}
	if [[ ${RELEASE_VERSION} =~ ^v[0-9]+.[0-9]+.[0-9]+$$ ]]; then \
		docker tag ${SERVICE_NAME}:${RELEASE_VERSION} ${ECR_REGISTRY}/${SERVICE_NAME}:latest; \
	fi

ci-push: build-chart-templates
	docker push ${ECR_REGISTRY}/${SERVICE_NAME}:${RELEASE_VERSION}
	if [[ ${RELEASE_VERSION} =~ ^v[0-9]+.[0-9]+.[0-9]+$$ ]]; then \
		docker push ${ECR_REGISTRY}/${SERVICE_NAME}:latest; \
	fi

	# Build & Release Helm Chart
	docker run --rm \
		-e "CHARTMUSEUM_TOKEN=${ORG_CHARTMUSEUM_JWT}" \
		-e "SERVICE_NAME=$(SERVICE_NAME)" \
		-e "RELEASE_VERSION=${RELEASE_VERSION}" \
		-e "HELM_REPOSITORY=internal" \
		-v ${PWD}/.divido/helm/web:/app ${ECR_REGISTRY}/service-chart-builder:1.0.0

	docker run --rm \
		-e "CHARTMUSEUM_TOKEN=${ORG_CHARTMUSEUM_JWT}" \
		-e "SERVICE_NAME=$(SERVICE_NAME)-wkr" \
		-e "RELEASE_VERSION=${RELEASE_VERSION}" \
		-e "HELM_REPOSITORY=internal" \
		-v ${PWD}/.divido/helm/wkr:/app ${ECR_REGISTRY}/service-chart-builder:1.0.0

	docker logout ${ECR_REGISTRY}

build-chart-templates:

	mkdir .divido/helm/web/templates || true
	docker run --rm \
		-e "SERMAN_RESOURCES=deployment,service,serviceaccount" \
		-v ${PWD}/.divido/helm/web/templates:/output \
		${ECR_REGISTRY}/serman:${SERMAN_VERSION}

	mkdir .divido/helm/wkr/templates || true
	docker run --rm \
		-e "SERMAN_RESOURCES=deployment,service,serviceaccount" \
		-v ${PWD}/.divido/helm/wkr/templates:/output \
		${ECR_REGISTRY}/serman:${SERMAN_VERSION}
