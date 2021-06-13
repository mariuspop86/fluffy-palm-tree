start:
	docker-compose up -d

stop:
	docker-compose down

run-test:
	docker-compose exec web php bin/console doctrine:migrations:migrate -n --env=test
	docker-compose exec web php bin/console doctrine:fixtures:load -n --group=APP --group=TEST --env=test
	docker-compose exec web php ./vendor/bin/phpunit
