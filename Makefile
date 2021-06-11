LOCALHOST_PROJECT_DIR := $(shell pwd)

# IMPORT CONFIG WITH ENVS. You can change the default config with `make cnf="config_special.env" up-dev`
cnf ?= $(LOCALHOST_PROJECT_DIR)/deploy/config.env
include $(cnf)

export $(shell sed 's/=.*//' $(cnf))

.DEFAULT_GOAL := help
# This will output the help for each task
# thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help:## This is help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: help

echo-project-dir:## Show current working directory.
	@echo $(LOCALHOST_PROJECT_DIR)
	@echo $(PROJECT_NAME)

.PHONY: echo-project-dir

print:## print
	@printenv

.PHONY: print

## Docker compose shortcuts
up-dev print-compose-file: COMPOSE_FILE=./docker-compose.yml
up-dev: ## Up current containers for dev
	docker-compose -f $(COMPOSE_FILE) up -d

run-dev: ##Run project
	docker run -it --name ${PROJECT_NAME}

print-compose-file:## print compose file
	@echo $(COMPOSE_FILE)

rename: ##Rename your project
	docker rename ${PROJECT_NAME} ${NEW_PROJECT_NAME}

.PHONY: up-dev print-compose-file

php-exec: CMD?=-r 'phpinfo();'
php-exec: ## Run any php command in our container
	docker exec ${PROJECT_NAME}-php php $(CMD)

.PHONY: php-exec

some-cmd:
	docker run \
	-it \
	--network some-network \
	--rm \
	mongo mongo \
	--host some-mongo \
	test \
	version \
	ps
composer: 
	composer require $(package)






