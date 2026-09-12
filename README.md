# DoQueue

DoQueue is a small task and assignment tracker built with Laravel, MySQL, Blade, and Vite.

## Requirements

- PHP 8.2 or newer
- Composer
- MySQL
- Node.js and npm

## Run locally

From Windows PowerShell:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Update the database values in `.env`, then run:

```powershell
php artisan migrate
npm ci
npm run build
php artisan test
php artisan serve
```

The local site is available at `http://127.0.0.1:8000`.

During development, `npm run dev` can be used instead of `npm run build` when Vite's live reload is needed.

## Security notes

- Passwords are hashed by Laravel.
- Login and registration requests are rate limited.
- Authenticated records are checked against the signed-in user's ID.
- Task attachments are stored on the private disk and are served through authorized controller actions.
- Blade escapes displayed user input.
- Do not commit `.env` or put real credentials in `.env.example`.

For production, use:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
SESSION_SECURE_COOKIE=true
DB_CONNECTION=mysql
```

Keep `APP_KEY`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in the server environment.

## Production checklist

Build the application on the server with:

```bash
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
php artisan migrate --force
php artisan optimize
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

Serve the application from the `public` directory. Use HTTPS in production. Do not run `php artisan storage:link`; task attachments are intentionally private.

## Free hosting

The free Render setup is not a good fit because its free service filesystem is temporary, its free database is PostgreSQL, and the free database expires after 30 days.

The closest free option for this MySQL application is an Oracle Cloud Always Free Ubuntu VM running Nginx, PHP-FPM, and MySQL. It requires account verification and availability depends on the selected region.
