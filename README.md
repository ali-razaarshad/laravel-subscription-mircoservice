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

# MySQL

# In your project’s .env file, replace the SQLite section with MySQL configuration:
# For MySQL Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=subscriptions_service
DB_USERNAME=root
DB_PASSWORD=   # (put your MySQL root password here, or leave blank if none)

# Now that Laravel is pointing to MySQL, re-run migrations:
php artisan migrate:fresh
