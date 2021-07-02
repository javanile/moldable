
start:
	@docker-compose up -d
	@docker-compose logs -f php

handbook:
	@git clone https://github.com/javanile/handbook

test-model-load-api:
	@docker-compose run --rm php ./vendor/bin/phpunit tests/Model/LoadApiTest.php

test-model-delete-api:
	@docker-compose run --rm php ./vendor/bin/phpunit tests/Model/DeleteApiTest.php

test-database-model-all:
	@docker-compose run --rm php ./vendor/bin/phpunit --filter ::testDatabaseModelAll tests/DatabaseModelTest.php
