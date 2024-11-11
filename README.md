<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

Challenge task.
Create API for News Aggregator.

## Deploy
you can find all needed commands in the `Makefile`.


## Entrypoints
* Home http://localhost:8090
* API Documentation http://localhost:8090/api/docs
* Debugger http://localhost:8090/telescope/requests

## Issues

### Permissions with Sail

Temp fix
* In WSL2 jump into the docker container as root by running this command vendor/bin/sail root-shell.
* Move up one directory cd...
* Recursively change the owner and group of the html folder to sail chown -R sail:sail html .
* Confirm the change ls -la. You should see the group and user sail on all the files and folders.
* Exit the container exit.

## Tools & Packages
* [zircote/swagger-php](https://zircote.github.io/swagger-php/guide/installation.html)
* [laravel-swagger-ui](https://github.com/wotzebra/laravel-swagger-ui)
* [docker swagger ui swaggerapi/swagger-ui](https://hub.docker.com/r/swaggerapi/swagger-ui)
