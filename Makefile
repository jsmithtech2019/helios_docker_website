# Makefile for Helios Website

# Variables
#VERSION := 1

help:	## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

build: ## Rebuild the containers.
	docker-compose build

up:	## Start the containers.
	docker-compose up

bash:	## Exec into the nginx container.
	docker-compose exec nginx bash

down:	## Stop the containers.
	docker-compose down -v --remove-orphans
