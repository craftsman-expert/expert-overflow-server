$scriptPath = split-path -parent $MyInvocation.MyCommand.Definition

# Include scripts
. "$scriptPath\modules\toast.ps1"

Clear

Toast-Info "Dropping database..."
docker compose exec php php bin/console doctrine:database:drop --env=test --if-exists -f

Toast-Info "Create database..."
docker compose exec php php bin/console doctrine:database:create --env=test

Toast-Info "Update schema..."
docker compose exec php php bin/console doctrine:schema:create --env=test

Toast-Info "Load fixtures..."
docker compose exec php php bin/console doctrine:fixtures:load --env=test --no-interaction

Toast-Info "Testing..."
docker compose exec php php bin/phpunit