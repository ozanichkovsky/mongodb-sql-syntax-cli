#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && [[ ! -e /.dockerinit ]] && exit 0

set -xe

# Install git (the php image doesn't have it) which is required by composer
apt-get update -yqq
apt-get install git -yqq

# Install mongodb driver
apt-get install libssl-dev -yqq
pecl install mongodb && docker-php-ext-enable mongodb

# Install xdebug
pecl install xdebug  && docker-php-ext-enable xdebug

#install composer
apt-get install curl -yqq && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer