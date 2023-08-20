$scriptPath = split-path -parent $MyInvocation.MyCommand.Definition

# Include scripts
. "$scriptPath\modules\toast.ps1"

CLear

Toast-Info "Building..."
docker-compose down
docker-compose build

Toast-Info "Up..."
docker-compose up -d

docker-compose ps

Toast-Info "Installing dependencies..."
docker-compose exec php composer install

Toast-Info "Synchronization of the container with the host..."
docker-compose exec php zip -0 -rq vendor.zip vendor
docker-compose cp php:/srv/www/vendor.zip vendor.zip
Invoke-Expression "$scriptPath\bin\7z.exe x -y vendor.zip"
Remove-Item .\vendor.zip -Force

Toast-Info "Create database..."
docker-compose exec php php bin/console doctrine:database:create --if-not-exists

Toast-Info "Update schema..."
docker-compose exec php php bin/console doctrine:schema:update --dump-sql -f

#Toast-Info "Load fixtures..."
#docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction --purge-with-truncate