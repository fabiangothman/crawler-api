
<h1 align="center">Crawler API</h1>

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Crawler API

This is a simple REST API built using the Laravel framework. It contains two main endpoints using GET method as follows:

- [/getData](https://cryptic-badlands-15292.herokuapp.com/getData).
- [/about](https://cryptic-badlands-15292.herokuapp.com/about).

### /getData

It's the endpoint that contains all the fetch, handles and managment of the data from the [source webpage](https://tentreem.mywhc.ca/devtest/products/) in a JSON format. By using some scraping/crawler techniques the API navegate througth all webpage pages, subpages and products and get the proper date necesary to build an information tree used to be shown in the [frontend app](https://github.com/fabiangothman/crawler-app).

### /about

This endpoint just returns the API's authors in a JSON format.

## Getting started

As the source code is using PHP and Laravel you're going to need some tools to run properly the project:
- Apache/Nginx.
- Database (not required).
- Pointing the root to the `/public` folder.
- Verify to have the proper permission in your network to access the [source webpage](https://tentreem.mywhc.ca/devtest/products/).

## License

Don't worry, the app is open-sourced software, feel free to check and what you need.
