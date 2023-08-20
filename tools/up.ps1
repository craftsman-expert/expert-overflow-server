Write-Host "Building..." -ForegroundColor Black -BackgroundColor Green
docker compose build

Write-Host "Up..." -ForegroundColor Black -BackgroundColor Green
docker compose up -d

docker compose ps