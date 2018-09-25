include .env

.PHONY: up down stop info

default: up

init:
	@echo "Init $(PROJECT_NAME)..."
	docker run --rm -it -v "$(PWD)/html:/var/www/html" -w "/var/www" wodby/drupal-php:$(PHP_TAG) composer create-project drupal-composer/drupal-project:8.x-dev html --stability dev --no-interaction

hooks:
	@echo "Install Git hooks..."
	cp git_hooks/pre-commit .git/hooks
	chmod a+x .git/hooks/pre-commit

up:
	@echo "Starting up containers for $(PROJECT_NAME)..."
	docker-compose up -d --remove-orphans

down: stop

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose stop

prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker-compose down -v

ps:
	@docker ps --filter name='$(PROJECT_NAME)*'

shell:
	docker exec -it $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") sh
