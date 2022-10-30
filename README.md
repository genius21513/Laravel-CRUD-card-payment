Ro run this aplication you need to intal on you pc: -composer -php 7.3/8 -xampp

1.clone or download and unpack download zip to your folder.

2.Open folder with your IDE and run this commands from treminal:

$ composer install
$ mv .env.example .env
$ php artisan cache:clear
$ composer dump-autoload
$ php artisan key:generate
3.Then connect to your database( do it in env configuration file in database section)

then Run command given below for start serving laravel
$ php artisan serve
$ php artisan migrate
Finally you can go to you browser and hit http://127.0.0.1:8000
enjoy :)
