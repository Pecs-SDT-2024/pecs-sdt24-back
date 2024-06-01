#!/usr/bin/make -f

#? help: Get more info on make commands.
help: Makefile
	@echo " Choose a command run in "$(PROJECT_NAME)":"
	@sed -n 's/^#?//p' $< | column -t -s ':' |  sort | sed -e 's/^/ /'
.PHONY: help

#? run: Run the application
run:
	php artisan serve
.PHONY: run

#? migrate: Apply available migrations
migrate:
	php artisan migrate
.PHONY: migrate
