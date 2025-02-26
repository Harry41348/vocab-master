# Laravel - React Template

### Table of Contents

**[Getting started](#getting-started)**<br>
**[Database seeding](#database-seeding)**<br>
**[Docker](#docker)**<br>
**[API specification](#api-specification)**<br>
**[Code Overview](#code-overview)**<br>
**[Testing API](#testing-api)**<br>
**[Authentication](#authentication)**<br>
**[Cross-Origin Resource Sharing (CORS)](#cross-origin-resource-sharing)**<br>

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Please note that `sail` can be found in `vendor/bin/sail`.

Clone the repository

    git clone git@github.com:Harry41348/laravel-react-template.git

Switch to the repo Laravel folder

    cd laravel-react-template/backend

Install all the dependencies using composer

    sail composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    sail artisan key:generate

Generate a new JWT authentication secret key

    sail artisan jwt:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    sail artisan migrate

Start the local development server

    sail up

You can now access the server at http://localhost

**TL;DR command list**

    git clone git@github.com:Harry41348/laravel-react-template.git
    cd laravel-react-template/backend
    sail composer install
    cp .env.example .env
    sail artisan key:generate
    sail artisan jwt:generate

**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    sail artisan migrate
    sail up

## Database seeding

**Populate the database with seed data. This can help you to quickly start testing the api or couple a frontend and start using it with ready content.**

Set the seeders you want to run in the DatabaseSeeder

    database/seeders/DatabaseSeeder.php

Run the database seeder and you're done

    php artisan db:seed

**_Note_** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh

## API specification

This application's API can be accessed at /api.

There is a JSON file which can be found in the /docs folder. This can be imported into Postman or Hoppscotch to get you started with the main API routes.

---

# Code overview

## Dependencies

-   [jwt-auth](https://github.com/tymondesigns/jwt-auth) - For authentication using JSON Web Tokens
-   [laravel-cors](https://github.com/barryvdh/laravel-cors) - For handling Cross-Origin Resource Sharing (CORS)

## Folders

-   `app` - Contains all the Eloquent models
-   `app/Enums` - Contains the custom enumerables
-   `app/Exceptions` - Contains the custom exceptions
-   `app/Helpers` - Contains the applications helpers
-   `app/Http/Controllers/Api` - Contains all the api controllers
-   `app/Http/Middleware` - Contains the JWT auth middleware
-   `app/Http/Requests/Api` - Contains all the api form requests
-   `app/Http/Resources` - Contains the model JSON resources
-   `app/Http/Responses` - Contains custom responses
-   `config` - Contains all the application configuration files
-   `database/factories` - Contains the model factory for all the models
-   `database/migrations` - Contains all the database migrations
-   `database/seeds` - Contains the database seeder
-   `routes` - Contains all the api routes defined in api.php file
-   `tests` - Contains all the application tests
-   `tests/Feature/Api` - Contains all the api tests

## Environment variables

-   `.env` - Environment variables can be set in this file

**_Note_** : You can quickly set the database information and other variables in this file and have the application fully working.

---

# Authentication

This applications uses JSON Web Token (JWT) to handle authentication. The token is passed with each request using the `Authorization` header with `Token` scheme. The JWT authentication middleware handles the validation and authentication of the token. Please check the following sources to learn more about JWT.

-   https://jwt.io/introduction/
-   https://self-issued.info/docs/draft-ietf-oauth-json-web-token.html

---

# Cross-Origin Resource Sharing (CORS)

This applications has CORS enabled by default on all API endpoints. The default configuration allows requests from `http://localhost:3000` and `http://localhost:4200` to help speed up your frontend testing. The CORS allowed origins can be changed by setting them in the config file. Please check the following sources to learn more about CORS.

-   https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS
-   https://en.wikipedia.org/wiki/Cross-origin_resource_sharing
-   https://www.w3.org/TR/cors
