#!/usr/bin/env bash
set -e

if [ ! -d vendor ]; then
    composer install --no-progress --no-interaction
    if grep -q ^DATABASE_URL= .env; then
        php bin/console doctrine:database:create --if-not-exists --no-interaction
        php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction
        php bin/console doctrine:fixtures:load --no-interaction
    fi
fi
