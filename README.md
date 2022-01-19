# E-Way Billing
## Built on LARAVEL 8 Platform.

## Server Requirements

- PHP >= 7.3
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Prerequisite

## Setup E-way
- Remove composer.lock
- composer install
- php artisan migrate:fresh --seed
- php artisan serve
- php artisan passport:install

## RUN 
- php artisan serve
- php artisan queue:work


# Third Party Packages
https://yajrabox.com/docs/laravel-datatables/master/engine-eloquent

# Dummy Data
- Run : php artisan tinker and execute following commands
- Transporter::factory()->count(40)->create();
- Consumer::factory()->count(40)->create();