# Makefile for Helios Website

# Variables
#VERSION := 1

help:	## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

run:	## Start the containers.
#	docker run -d --rm --name nginx -v ~/Desktop/fall_2019/eset_419/helios-docker-framework/:/host/ -p 80:80 helios/nginx:0.7
	docker-compose up -d

bash:	## Exec into the nginx container.
	docker-compose exec nginx bash

down:	## Stop the containers.
	docker-compose down -v --remove-orphans