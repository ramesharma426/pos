# Local Development

This project runs in Docker (PHP 8.3 + MySQL 8) with a Vite-built Vue 3 SPA.

## Stack (after upgrade)

| Layer     | Version                              |
|-----------|--------------------------------------|
| Backend   | Laravel 13, PHP 8.3                  |
| Auth      | Sanctum 4 (SPA session cookies)      |
| Frontend  | Vue 3.5, Vite 8, vue-router 4        |
| Database  | MySQL 8                              |

## Run the backend (Docker)

```bash
cp .env.example .env         # first time only
# .env is preconfigured for compose: DB_HOST=db, DB_PASSWORD=root,
# APP_URL=http://localhost:8000, VITE_MIX_APP_URL=http://localhost:8000/api
docker compose up -d --build
```

The `app` container entrypoint waits for MySQL, runs `composer install` (if
`vendor/` is missing), generates the app key, and runs migrations. Then:

```bash
docker compose exec app php artisan db:seed   # demo data + users
```

App: http://localhost:8000  •  Login: `admin@pos.com` / `password`

## Build the frontend

The API base URL is baked into the bundle at build time from
`VITE_MIX_APP_URL`, so rebuild whenever that URL changes.

```bash
npm install --ignore-scripts     # summernote (transitive) has a broken husky prepare script
npm rebuild admin-lte            # admin-lte provisions its plugins/ folder via an install script
npm run build                    # outputs to public/build
```

Notes:
- `--ignore-scripts` is required because a transitive dependency's `prepare`
  script (`husky install`) fails; we then run only admin-lte's install script.
- `vite.config.js` sets `css.lightningcss.errorRecovery` because Vite 8's
  LightningCSS minifier rejects legacy IE star-hacks in vendored CSS (selectize).

## Debugging with Playwright

Headless smoke tests live in `scripts/`:

```bash
node scripts/pw-debug.mjs    # loads /, reports console/network/page errors, screenshots
node scripts/pw-login.mjs    # logs in and asserts it reaches the authenticated app
```
