#!/bin/bash

docker-php-ext-install pdo_mysql
exec docker-php-entrypoint php-fpm