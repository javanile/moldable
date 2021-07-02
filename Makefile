
start:
	@docker-compose up -d
	@docker-compose logs -f php


test-database-model-all:
	@docker-compose run --rm php ./vendor/bin/phpunit --filter ::testDatabaseModelAll tests/DatabaseModelTest.php