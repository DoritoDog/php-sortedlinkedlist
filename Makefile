.PHONY: help build up down shell test install clean coverage phpstan phpcs phpcbf check

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

build: ## Build Docker images
	docker-compose build

up: ## Start containers
	docker-compose up -d

down: ## Stop and remove containers
	docker-compose down

restart: down up ## Restart containers

shell: ## Open shell in PHP container
	docker-compose exec php sh

test: ## Run PHPUnit tests
	docker-compose run --rm test

test-watch: ## Run tests in watch mode (requires manual rerun)
	docker-compose run --rm test vendor/bin/phpunit --testdox

install: ## Install composer dependencies
	docker-compose run --rm php composer install

update: ## Update composer dependencies
	docker-compose run --rm php composer update

coverage: ## Generate code coverage report
	docker-compose run --rm test vendor/bin/phpunit --coverage-html coverage

coverage-text: ## Show code coverage in terminal
	docker-compose run --rm test vendor/bin/phpunit --coverage-text

clean: ## Clean up containers, volumes, and generated files
	docker-compose down -v
	rm -rf vendor/ coverage/ .phpunit.result.cache

rebuild: clean build ## Clean and rebuild everything

logs: ## Show container logs
	docker-compose logs -f

ps: ## Show running containers
	docker-compose ps

composer: ## Run arbitrary composer command (usage: make composer CMD="require package")
	docker-compose run --rm php composer $(CMD)

phpunit: ## Run arbitrary phpunit command (usage: make phpunit CMD="--filter testName")
	docker-compose run --rm test vendor/bin/phpunit $(CMD)

phpstan: ## Run PHPStan static analysis
	docker-compose run --rm test vendor/bin/phpstan analyse

phpcs: ## Run PHP_CodeSniffer to check coding standards
	docker-compose run --rm test vendor/bin/phpcs

phpcbf: ## Run PHP Code Beautifier and Fixer to auto-fix coding standards
	docker-compose run --rm test vendor/bin/phpcbf

check: ## Run all quality checks (PHPStan, PHPCS, PHPUnit)
	@echo "Running PHPStan..."
	@docker-compose run --rm test vendor/bin/phpstan analyse
	@echo "\nRunning PHPCS..."
	@docker-compose run --rm test vendor/bin/phpcs
	@echo "\nRunning PHPUnit..."
	@docker-compose run --rm test vendor/bin/phpunit