include .env

.PHONY: up down stop prune ps shell

default: up

up:
	@echo "Starting up containers for for $(PROJECT_NAME)..."
	docker-compose -f docker-compose.yml -f docker-compose.override.$(COMPOSE_OVERRIDE).yml up -d --remove-orphans

down: stop

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose -f docker-compose.yml -f docker-compose.override.$(COMPOSE_OVERRIDE).yml stop

prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker-compose -f docker-compose.yml -f docker-compose.override.$(COMPOSE_OVERRIDE).yml down -v

ps:
	@docker ps --filter name='$(PROJECT_NAME)*'

shell:
	docker exec -it $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") sh
