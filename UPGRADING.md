# Upgrading

Commands assume that they are running within the Drupal root directory, use globally installed Composer script and
locally installed Drush dependency.

**NB! It is mandatory to make a backup of both the database and all Drupal files before upgrading!**

## 1.0.0 - 20.01.2022

```shell
git pull
composer install --no-dev
vendor/bin/drush sset system.maintenance_mode 1
vendor/bin/drush cr
vendor/bin/drush cim
vendor/bin/drush sset system.maintenance_mode 0
vendor/bin/drush cr
```

## 1.1.0 - 07.02.2022

NB! Please note that there is a need to replace the `<full-path-to-drupal>` in the shell update command.

```shell
vendor/bin/drush sset system.maintenance_mode 1
git pull
composer install --no-dev
vendor/bin/drush cr
vendor/bin/drush updatedb
vendor/bin/drush cim
vendor/bin/drush locale-import --override=none et <full-path-to-drupal>/config/locales/et.po
vendor/bin/drush locale-import --override=none ru <full-path-to-drupal>/config/locales/ru.po
vendor/bin/drush sset system.maintenance_mode 0
vendor/bin/drush cr
```
