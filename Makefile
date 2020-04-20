# Makefile for Helios Website

# Variables
VERSION := 19.2
NAME ?= 'helioscapstone'

help:	## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

build: ## Rebuild the containers.
	docker-compose build

up:	## Start the containers.
	docker-compose up --force-recreate

bash:	## Exec into the nginx container.
	docker-compose exec nginx bash

down:	## Stop the containers.
	docker-compose down -v --remove-orphans

name:	## Change name of website to match existing (NAME='new name')
	# If sed commands fail (Mac host) update to -i.bak
	sed -i.bak 's/helioscapstone/${NAME}/g' conf/default.conf
	sed -i.bak 's/helioscapstone/${NAME}/g' code/insert_module.php
	sed -i.bak 's/helioscapstone/${NAME}/g' code/insert_admin.php
	sed -i.bak 's/helioscapstone/${NAME}/g' code/remove_admin.php