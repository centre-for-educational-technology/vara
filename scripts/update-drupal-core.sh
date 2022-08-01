#!/bin/bash

print_line() {
  echo '-----'
}

print_line
echo 'Updating Drupal core via Composer'
print_line

echo ''
composer outdated "drupal/*"
chmod u+w web/sites/default
composer update drupal/core "drupal/core-*" --with-all-dependencies
chmod u-w web/sites/default
echo ''

echo ''
./vendor/bin/drush updatedb
./vendor/bin/drush cache:rebuild
echo ''
