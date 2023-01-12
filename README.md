<div align="center">
    <h1>INVENTORY APP</h1>
</div>
<p align="center">
<img src="https://github.com/myndra1805/inventory-app/blob/main/public/images/logo.png?raw=true" alt="Logo Inventory App">
</p>

## Description

This project is a website-based storage information system. This system functions to manage the goods owned. There are three user roles in this system:
1. Super Admin
   - Login
   - Dashboard
   - CRUD product
   - CRUD product type
   - CRUD product unit
   - CRUD account admin and warehouse
   - CRUD suppliers
   - CRUD transactions
2. Admin
   - Login
   - Dashboard
   - CRUD product
   - CRUD product type
   - CRUD product unit
   - CRUD account warehouse
   - CRUD suppliers
   - Create, update, dan read transactions
3. Warehouse
   - Login
   - Dashboard
   - CRUD product
   - CRUD product type
   - CRUD product unit
   - CRUD suppliers
   - Create dan read transactions

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
