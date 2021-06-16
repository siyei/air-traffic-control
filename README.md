# Air Traffic Control System (ATCS)
The ATCS will allow the queuing and dequeuing of aircraft (AC).

## Installation
Clone this repository
```bash
git clone git@github.com:siyei/air-traffic-control.git 
```
Create the .env by copying the example file and update de values to your own system
```bash
cp .env.example .env
```
Change the values of DB_* variables with your own \
Create an empty database \
Install dependencies with composer 
```bash
composer install
```

Run the migrations
```bash
php artisan migrate
```