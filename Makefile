init:
	docker-compose exec php composer install

test:
	docker-compose exec php vendor/bin/phpunit -c phpunit.xml ${ARGS}
