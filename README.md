# Subscriptions Microservice (Laravel 12)

Handles:
- Subscription renewals (auto & manual)
- Queue-based email reminders
- Admin API for reports
- Daily cron processing
- Clean architecture (jobs + events + services)

## Tech
- Laravel 12
- SQLite (dev)
- Sanctum (API Tokens)
- Queues: database driver

## Setup
```bash
composer install
cp .env.example .env
php artisan key:generate

# SQLite
mkdir database
type nul > database\database.sqlite

php artisan migrate
