# Tengaz Task
# Backend Api for User / Product CRUDs

## Ahmed Badr
## Laravel - Sanctum - SQLite

### Installation

Clone the repository

    git clone git@github.com:ahmedBadr1/tenjaz-task.git

Switch to the repo folder

    cd tenjaz-task

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

For using SQLite create a file named Database, it is already configured at .env file

    touch database/database.sqlite

Generate a new application key

    php artisan key:generate

Create Storage Link for uploaded files 

    php artisan storage:link

Install all the dependencies using npm

    npm install && npm run dev


Run the database migrations (**make sure the connection is set in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone git@github.com:ahmedBadr1/tenjaz-task.git
    cd tenjaz-task
    composer install
    cp .env.example .env
    php artisan key:generate

**Make sure you set the correct database connection information before running the migrations**

    php artisan migrate

**Make sure you set the connection between storage and public directory (run only once in project lifetime)**

    php artisan storage:link 

### Database seeding

**Populate the database with seed data with relationships which includes users, articles, comments, tags, favorites and follows. This can help you to quickly start testing the api or couple a frontend and start using it with ready content.**

Open the DummyDataSeeder and set the property values as per your requirement

    database/seeders/DatabaseSeeder.php

Run the database seeder and you're done

    php artisan db:seed

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:fresh --seed

**Accounts**

    normal@demo.com   
    sliver@demo.com
    gold@demo.com

you can run test at sqlite in memory

    php artisan test

You can now access the server at http://localhost:8000

    php artisan serve

You can now access the Vite server at http://localhost:5173

    npm run dev

***Enjoy*** 
    
    
