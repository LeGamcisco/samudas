# SAMUDAS
## Installation
1. Download or Clone this repository
```bash
```
2. `composer install` on `trudas-laravel` directory
3. `cp .env.example .env` Create .env file from example & configure
4.  Generate new environtment key
```bash 
php artisan key:generate
```
5.  Migrate & seeding database
```bash
php artisan migrate --seed
```
6. Import Task Scheduler
