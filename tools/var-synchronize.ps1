$scriptPath = split-path -parent $MyInvocation.MyCommand.Definition

# Include scripts
. "$scriptPath\modules\toast.ps1"

Toast-Info "Synchronization var..."
docker-compose exec php zip -0 -rq var.zip var
docker-compose cp php:/srv/www/var.zip var.zip
Invoke-Expression "$scriptPath\bin\7z.exe x -y var.zip"
Remove-Item .\var.zip -Force