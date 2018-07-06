#!/bin/sh

# Update composer dependencies.
composer install --prefer-dist

# Install Drupal.
vendor/bin/robo build:conf -e local -s default
