#!/bin/bash

print_line() {
  echo '-----'
}

print_line
echo 'Updating Drupal core via Composer'
print_line

echo ''
composer outdated "drupal/*"
composer update drupal/core "drupal/core-*" --with-all-dependencies
echo ''

echo ''
./vendor/bin/drush updatedb
./vendor/bin/drush cache:rebuild
echo ''
