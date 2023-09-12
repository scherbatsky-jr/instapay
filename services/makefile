#!make
include .env
export $(shell sed 's/=.*//' .env)

build: image.mariadb

clean:
	rm -rf */data

export: export.mariadb

export.mariadb:
	@printf "\033[0;32m>>> Dumping database data\033[0m\n"
	docker exec -it ${PROJECT}-mariadb mysqldump \
		-u$${MARIADB_USER} \
		-p$${MARIADB_PASSWORD} \
		--no-data \
		$${MARIADB_DATABASE} > mariadb/fixtures/$${MARIADB_DATABASE}.sql
	docker exec -it ${PROJECT}-mariadb mysqldump \
		-u$${MARIADB_USER} -p$${MARIADB_PASSWORD} --no-create-info \
		-c $${MARIADB_DATABASE} | awk '{gsub(/\),/, "&\n")}1' | awk '{gsub(/ VALUES /, " VALUES\n")}1' \
		>> mariadb/fixtures/$${MARIADB_DATABASE}.sql
	rm mariadb/fixtures/$${MARIADB_DATABASE}.sql.gz
	gzip -k mariadb/fixtures/$${MARIADB_DATABASE}.sql
 
image.mariadb:
	@printf "\033[0;32m>>> Building database server image\033[0m\n"
	docker build -t ${PROJECT}/mariadb:local mariadb

start:
	@printf "\033[0;32m>>> Starting local services\033[0m\n"
	docker-compose -f docker-compose.local.yml up -d

stop:
	@printf "\033[0;32m>>> Stopping local services\033[0m\n"
	docker-compose -f docker-compose.local.yml down \
		--rmi local \
		--remove-orphans \
		--volumes
