#!/bin/bash

./vendor/bin/sail composer update
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan db:seed --class='PermissionSeeder'
./vendor/bin/sail artisan db:seed --class='RoleSeeder'
./vendor/bin/sail artisan db:seed --class='UserSeeder'
./vendor/bin/sail artisan migrate --force
