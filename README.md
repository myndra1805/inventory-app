<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Description

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

## Tech Stack

For the tech stack, I'm was using :
1. [Laravel 9](https://laravel.com/)
2. [Bootstrap 5](https://getbootstrap.com/)
3. [Datatables](https://datatables.net/)
4. [JQuery](https://jquery.com/)
5. and many utilities

## Build Setup

1. Enter email and database configuration in .env file
   ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=YOUR_DB_NAME
    DB_USERNAME=YOUR_DB_USERNAME
    DB_PASSWORD=YOUR_DB_PASSWORD

    MAIL_MAILER=YOUR_MAIL_MAILER
    MAIL_HOST=YOUR_MAIL_HOST
    MAIL_PORT=YOUR_MAIL_PORT
    MAIL_USERNAME=YOUR_MAIL_USERNAME
    MAIL_PASSWORD=YOUR_MAIL_PASSWORD
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=YOUR_MAIL_FROM_ADDRESS
    MAIL_FROM_NAME="${APP_NAME}"
   ```
2. Migrate and seed database 
   ```
   php artisan migrate --seed
   ```
3. Running server
   ```
   php artisan serve
   ```
