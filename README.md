# How to run this project

You can read more detailed documentation in the back-end and front-end directories. The following instructions will be how to get the basics up and running in this project.

From the back-end directory, run these commands:

    git clone git@github.com:Harry41348/laravel-react-template.git
    cd laravel-react-template/backend
    sail composer install
    cp .env.example .env
    sail artisan key:generate
    sail artisan jwt:generate

_Please note that `sail` can be found in `vendor/bin/sail`._

From the front-end directory, run these commands:

    npm install
    npm run dev
