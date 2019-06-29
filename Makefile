include .env.local


# Init
######
init:
	cp .env .env.local
	make build


# Container management
######################
up:
	docker-compose up -d

down:
	docker-compose down

reup: down up

build: down
	docker-compose build --no-cache --build-arg USER_ID=${USER_ID}


# Container interaction
#######################
connect_php_bash:
	docker exec -it my-movies_php bash

connect_mysql_cli:
	docker exec -it my-movies_mysql sh -c "mysql -uroot -p${MYSQL_ROOT_PASSWORD}"

logs_php:
	docker logs -f my-movies_php


# Composer
##########
composer_install:
	docker exec my-movies_php bash -c "composer install"

composer_update:
	docker exec my-movies_php bash -c "composer update"


# Utils
#########
test:
	docker exec my-movies_php bash -c "vendor/bin/phpstan analyse --level 7 src"

cache_delete:
	rm -r var/cache/*

load_diary:
	docker exec my-movies_php bash -c "php bin/console app:load-diary"

get_diary:
	docker exec my-movies_php bash -c "php bin/console app:get-diary"


# Database
##########
db_migration:
	docker exec my-movies_php bash -c "php bin/console make:migration"

db_migration_generate:
	docker exec my-movies_php bash -c "php bin/console doctrine:migrations:generate"

db_migration_diff:
	docker exec my-movies_php bash -c "php bin/console doctrine:migrations:diff"

db_migration_migrate:
	docker exec my-movies_php bash -c "php bin/console doctrine:migrations:migrate"

db_flush:
	docker exec my-movies_mysql bash -c 'mysql -u${MYSQL_USER} -p${MYSQL_PASSWORD} <<< "USE $(MYSQL_DATABASE); DELETE FROM watch_date; DELETE FROM movie;"'

db_import:
	docker my-movies_cp $(FILE) mysql:/tmp/dump.sql
	docker exec my-movies_mysql bash -c 'mysql -u${MYSQL_USER} -p${MYSQL_PASSWORD} < /tmp/dump.sql'
	docker exec my-movies_mysql bash -c 'rm /tmp/dump.sql'

db_export:
	docker exec my-movies_mysql bash -c 'mysqldump --databases --add-drop-database -u${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE} > /tmp/dump.sql'
	docker cp my-movies_mysql:/tmp/dump.sql tmp/my-movies-`date +%Y-%m-%d-%H-%M-%S`.sql
	docker exec my-movies_mysql bash -c 'rm /tmp/dump.sql'