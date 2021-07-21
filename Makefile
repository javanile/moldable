
.PHONY: docs

start:
	@docker-compose up -d --force-recreate
	@docker-compose logs -f php

dev:
	@docker-compose up -d --force-recreate
	@docker-compose logs -f php

docs:
	@docker-compose run --rm php ./handbook/bin/handbook
	@git add .

handbook:
	@git clone https://github.com/javanile/handbook

pull:
	@git pull
	@cd handbook && git pull

test-model-load-api:
	@docker-compose run --rm php ./vendor/bin/phpunit tests/Model/LoadApiTest.php

test-model-delete-api:
	@docker-compose run --rm php ./vendor/bin/phpunit tests/Model/DeleteApiTest.php

test-database-model-all:
	@docker-compose run --rm php ./vendor/bin/phpunit --filter ::testDatabaseModelAll tests/DatabaseModelTest.php
