# TLU H5P

## Requirements

* PHP version 7.4
* MySQL version 5.7+

## Installation

* `composer install`
* Setup Drupal site with language set to Estonian
* Make sure that configuration synchronisation directory is set to `$settings['config_sync_directory'] = '../config/sync';`
* Set site identifier to match the one for synchronised data by running `vendor/bin/drush cset system.site uuid <uuid> -y`
  * You can get site unique identifier [here](https://github.com/centre-for-educational-technology/tlu-h5p/blob/main/config/sync/system.site.yml)
* Import configuration data with `vendor/bin/drush cim -y`

## Development

* Update site settings with `vendor/bin/drush cim`
* Export site settings with `vendor/bin/drush cex`
