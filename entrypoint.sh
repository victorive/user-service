#!/bin/bash

set -e

composer install --no-interaction
composer dump-autoload
bin/console cache:clear
rm -rf var/log/*
rm -rf coverage-report-*
rm -rf .php-cs-fixer.cache
rm -rf .phpunit.result.cache
bin/console doctrine:database:create --if-not-exists
bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
