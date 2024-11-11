#!make

include .env

# commands to run project on your local environment ONLY!

# install project on scratch
install:
	./vendor/bin/sail up -d
	./vendor/bin/sail artisan migrate

# run project and recreate containers
up:
	./vendor/bin/sail up -d

# stop project
stop:
	./vendor/bin/sail stop

# stop project
start:
	./vendor/bin/sail start

# down project to recreate containers on the next up
down:
	./vendor/bin/sail down

test:
	./vendor/bin/sail phpunit

# open container to run commands directly in container
open-php:
	./vendor/bin/sail exec laravel sh

api-documentation-generate:
	./vendor/bin/sail exec laravel bash -c "./vendor/bin/openapi app -o resources/swagger/v${APP_API_VERSION}/openapi.json --format json"
