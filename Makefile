.PHONY: up down build serve migrate clearcache

up:
	docker-compose config -q && \
	docker-compose up --force-recreate

down:
	docker-compose config -q && \
	docker-compose down -v

build:
	docker-compose config -q && \
	docker-compose build --pull

serve:
	docker-compose config -q && \
	docker-compose exec laravel php artisan serve --host 0.0.0.0

migrate:
	docker-compose config -q && \
	docker-compose exec laravel php artisan migrate:refresh

passport-init:
	docker-compose config -q && \
	docker-compose exec laravel php artisan passport:install

composer-install:
	docker-compose config -q && \
	docker-compose exec laravel composer install

composer-update:
	docker-compose config -q && \
	docker-compose exec laravel composer update

npm-install:
	docker-compose config -q && \
	docker-compose run --rm node npm i

bower-install:
	docker-compose config -q && \
	docker-compose run --rm node bower install -fF

gulp:
	docker-compose config -q && \
	docker-compose run --rm node gulp

clearcache:
	docker-compose exec laravel php artisan cache:clear; \
	docker-compose exec laravel php artisan clear-compiled; \
	docker-compose exec laravel php artisan config:clear; \
	docker-compose exec laravel php artisan route:clear; \
	docker-compose exec laravel php artisan view:clear; \
	docker-compose exec laravel php artisan optimize; \
	true
