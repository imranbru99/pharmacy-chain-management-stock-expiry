# Deployment

## Docker (local / staging)

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed --force
docker compose exec app npm install && npm run build
```

App URL via nginx: `http://localhost:8080`

Services: `app` (php-fpm 8.3), `nginx`, `mysql`, `redis`, `queue`.

## Production notes

- Set `DEMO_MODE=false` on production installs.
- Use MySQL (`DB_CONNECTION=mysql`) and Redis for cache/queue.
- Run `php artisan config:cache` and `php artisan route:cache` after deploy.
- Horizon or `queue:work` for async jobs; compose includes a sample `queue` worker.

## Demo refresh

```bash
php artisan demo:reset --force
```

Scheduled weekly in `routes/console.php` when `DEMO_MODE=true` on `staging` / `demo` environments.
