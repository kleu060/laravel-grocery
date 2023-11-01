## About This Project

This Application will Import all the products from a grocery store (Pak n Save).  List all the products, and their prices  and display product price history in a line graph.

This application is built using Laravel, and the front end is implemented using Inertia/Vue frontend stacks.

## Installation
1.  Download the repository
2.  run ```compose i```
3.  run ```npm ci```
4.  run ```php artisan migrate```
5.  run ```php artisan storage:link```
6.  run `npm run dev`
7.  run ```php artisan serve```
8.  Enjoy!

## Dependencies
1.  laravel/breeze
2.  arielmejiadev/larapex-charts


## Data Retrival
A commandline tools PhantomJS (https://phantomjs.org/quick-start.html) is used to retrieve data from Pak N Save website.
