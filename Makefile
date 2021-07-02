
start:
	@docker-compose up -d
	@docker-compose logs -f php

handbook:
	git clone https://github.com/javanile/handbook

test-database-model-all:
	@docker-compose run --rm php ./vendor/bin/phpunit --filter ::testDatabaseModelAll tests/DatabaseModelTest.php
