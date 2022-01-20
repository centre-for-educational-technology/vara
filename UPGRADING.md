# Upgrading

Commands assume that they are running within the Drupal root directory, use globally installed Composer script and
locally installed Drush dependency.

**NB! It is mandatory to make a backup of both the database and all Drupal files before upgrading!**

## 1.0.0 - 20.01.2022

```shell
git pull
composer install
vendor/bin/drush sset system.maintenance_mode 1
vendor/bin/drush cr
vendor/bin/drush cim
vendor/bin/drush sset system.maintenance_mode 0
vendor/bin/drush cr
```
