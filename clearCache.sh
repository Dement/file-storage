#!/bin/bash

if [ "$1" == "composer" ]; then
    composer update
fi

chmod -R 777 var/cache/
chmod -R 777 var/logs/
chmod 777 bin/symfony_requirements

bin/console doctrine:cache:clear-metadata
bin/console doctrine:cache:clear-query
bin/console doctrine:cache:clear-result

bin/console --env=prod cache:clear
bin/console --env=dev cache:clear
bin/console --env=test cache:clear

bin/console --env=test testdb:drop
bin/console --env=test testdb:create

rm -R var/cache/*