#!/bin/bash
set -e
cd /var/www/html

# --- Environment ---
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Ensure writable storage
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

# --- Dependencies (only if missing, to keep restarts fast) ---
if [ ! -d vendor ]; then
  composer install --no-interaction --no-progress --optimize-autoloader
fi
if [ ! -d node_modules ]; then
  npm install --ignore-scripts --no-audit --no-fund
fi

# Generate APP_KEY if missing (needs vendor/autoload first)
if ! grep -q '^APP_KEY=[A-Za-z0-9:]' .env; then
  php artisan key:generate --force
fi

# --- Database (SQLite) ---
touch database/database.sqlite
php artisan migrate --seed --force

echo "Starting Laravel dev server on :3000 and Vite on :5173"

# --- Servers ---
php artisan serve --host=0.0.0.0 --port=3000 &
APP_PID=$!

# Vite dev server: bind all interfaces, expose public origin for the preview proxy
npx vite --host 0.0.0.0 --port 5173 --strictPort &
VITE_PID=$!

# Exit (and let compose restart) if either process dies
wait -n
EXIT=$?
kill $APP_PID $VITE_PID 2>/dev/null || true
exit $EXIT
