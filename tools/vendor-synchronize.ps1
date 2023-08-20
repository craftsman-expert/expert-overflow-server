$scriptPath = split-path -parent $MyInvocation.MyCommand.Definition

# Include scripts
. "$scriptPath\modules\toast.ps1"

Toast-Info "Synchronization vendor..."
docker-compose exec php zip -0 -rq vendor.zip vendor
docker-compose cp php:/srv/www/vendor.zip vendor.zip
Invoke-Expression "$scriptPath\bin\7z.exe x -y vendor.zip"
Remove-Item .\vendor.zip -Force