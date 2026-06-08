# 🚀 Laravel on Render — Deployment Guide

## Files to add to your Laravel project

Copy these files into the **root of your Laravel project**:

```
your-laravel-project/
├── Dockerfile              ← copy this
├── render.yaml             ← copy this
└── docker/
    ├── nginx.conf          ← copy this
    ├── supervisord.conf    ← copy this
    └── entrypoint.sh       ← copy this
```

---

## Step-by-Step Deployment

### 1. Push files to GitHub
```bash
git add .
git commit -m "Add Render deployment config"
git push origin main
```

### 2. Deploy on Render

**Option A — Using render.yaml (recommended, deploys app + database together)**
1. Go to https://dashboard.render.com
2. Click **"New"** → **"Blueprint"**
3. Connect your GitHub repo
4. Render will detect `render.yaml` and set up everything automatically

**Option B — Manual setup**
1. Go to https://dashboard.render.com
2. Click **"New"** → **"Web Service"**
3. Connect your GitHub repo
4. Set:
   - **Language:** Docker
   - **Branch:** main
   - **Region:** Oregon (US West) or closest to you

### 3. Add Environment Variables
In the Render dashboard → your service → **Environment**, add:

| Key              | Value                        |
|------------------|------------------------------|
| `APP_NAME`       | Your app name                |
| `APP_ENV`        | production                   |
| `APP_DEBUG`      | false                        |
| `APP_KEY`        | (auto-generated or run `php artisan key:generate --show`) |
| `DB_CONNECTION`  | pgsql (or mysql)             |
| `DB_HOST`        | (from your Render DB)        |
| `DB_PORT`        | 5432                         |
| `DB_DATABASE`    | laravel                      |
| `DB_USERNAME`    | laravel                      |
| `DB_PASSWORD`    | (from your Render DB)        |

### 4. Create the Database (if not using render.yaml)
1. Click **"New"** → **"PostgreSQL"**
2. Copy the connection details into your web service's environment variables

---

## Using MySQL instead of PostgreSQL?

In `render.yaml`, replace the `databases` section and update env vars:
```yaml
- key: DB_CONNECTION
  value: mysql
- key: DB_PORT
  value: "3306"
```

> ⚠️ Render's free PostgreSQL expires after 90 days. Upgrade to a paid plan for production.

---

## Troubleshooting

**Build fails with "composer install" error**
→ Make sure your `composer.json` is committed to git.

**500 error after deploy**
→ Check that `APP_KEY` is set in environment variables.

**Database connection refused**
→ Verify `DB_HOST`, `DB_PORT`, `DB_USERNAME`, and `DB_PASSWORD` match your Render DB.

**Migrations not running**
→ Check deploy logs — the entrypoint script runs `php artisan migrate --force` automatically.

**Storage/upload issues**
→ Render's filesystem is ephemeral. Use AWS S3 or Cloudflare R2 for file storage:
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```
