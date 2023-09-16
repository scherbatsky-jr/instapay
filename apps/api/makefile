docker.composer:
	@printf "\033[0;32m>>> Running composer install\033[0m\n"
	docker run --rm --interactive --tty \
		--volume $(shell pwd):/app \
		--user $(shell id -u):$(shell id -g) \
		composer install

docker.start:
	@printf "\033[0;32m>>> Starting app via docker-compose\033[0m\n"
	USER_ID=$$(id -u) docker-compose -f ./docker/docker-compose.local.yml up -d
	@printf "\033[0;32m>>> Siamcardeal Services API running at http://localhost:20198\033[0m\n"

docker.stop:
	@printf "\033[0;32m>>> Stopping app via docker-compose\033[0m\n"
	docker-compose -f ./docker/docker-compose.local.yml down --rmi local -v --remove-orphans

fix:
	@printf "\033[0;32m>>> Fixing code\033[0m\n"
	docker exec -ti siamcardeal-services-api vendor/bin/php-cs-fixer fix

migrate:
	@printf "\033[0;32m>>> Fixing code\033[0m\n"
	docker exec -ti siamcardeal-services-api php artisan migrate

test:
	@printf "\033[0;32m>>> Running tests\033[0m\n"
	docker exec -ti siamcardeal-services-api vendor/bin/phpunit
