# Hotel Management System

Laravel 13 with React, Inertia.js, and Tailwind CSS. SQLite is the default for local development; MySQL is also supported.

## Requirements

- PHP 8.3 or newer with PDO SQLite for local development (or PDO MySQL when using MySQL)
- Composer
- Node.js and npm

## Local setup

In PowerShell, from the project directory:

```powershell
composer install
npm install
if (!(Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
php artisan migrate --seed
npm run dev
```

In a second terminal, run `php artisan serve` and open the local address it prints.
The example environment uses SQLite. To use MySQL, set `DB_CONNECTION=mysql` and
configure `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`
in your local `.env` before migrating.

Create the first administrator using a private interactive password prompt:

```powershell
php artisan staff:create --role=admin
```

Additional receptionists can be created with `php artisan staff:create` (or
`--role=receptionist`). Passwords must be at least 12 characters and are entered
without being echoed to the terminal. Staff sign in at `/login`; the dashboard
is at `/staff/dashboard`.

`php artisan migrate:fresh --seed` rebuilds the database from scratch and deletes
existing records; use it only when that reset is intended.

No staff accounts, hotel prices, room inventory, or sample guest records are
seeded. Add real hotel information before publishing or accepting bookings.

If Artisan reports a dotenv parse error, back up the local environment file and
recreate it from the example before generating the application key:

```powershell
$backup = ".env.backup.$(Get-Date -Format 'yyyyMMddHHmmss')"
Copy-Item .env $backup
Copy-Item .env.example .env -Force
php artisan key:generate
```

Reapply any local database settings to `.env` before running migrations. Both
environment files are excluded from version control.

## Foundation

Stage 1 adds users with staff roles, guests, room types, rooms, bookings, staff
shifts, AI conversation transcripts, staff messages, model factories, and
migrations. Stage 2 adds staff sign-in, role-aware authorization, and the
operations dashboard for rooms, room types, guests, and bookings. Receptionists
can manage guests and bookings; only administrators can change inventory.
Availability checking and conflict prevention are implemented in Stage 3.
