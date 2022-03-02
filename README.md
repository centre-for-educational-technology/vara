# Vara

## Requirements

* PHP version 8.0
* MySQL version 5.7+

## Installation

* `composer install`
* Setup Drupal site with language set to **English** with profile set to **Standard**
* Make sure that **configuration synchronisation directory** is set to `$settings['config_sync_directory'] = '../config/sync';`
* Set site identifier to match the one for synchronised data by running `vendor/bin/drush cset system.site uuid ca6743de-d1c6-4775-b2cb-faace125f90c -y`
* Remove default shortcuts by running `vendor/bin/drush ev '$entity_type = "shortcut"; $storage_handler = \Drupal::entityTypeManager()->getStorage($entity_type); $storage_handler->delete($storage_handler->loadMultiple(\Drupal::entityQuery($entity_type)->execute()));'`
* Import configuration data with `vendor/bin/drush cim -y`
* Import translations
  * Current translations are in Estonian (`et`) and Russian (`ru`)
  * Translations can be imported using specific command: `./scripts/import-all-languages.sh`
* Add H5P.MathDisplay library
  * Read documentation for the [H5P MathInput](https://git.drupalcode.org/project/h5p_math_input) module or go directly
  to the [H5P documentation](https://h5p.org/mathematical-expressions), download the library package and upload that
  through the library admin page.

### Update

Update could depend on changes made to the repository. There are a few different actions to be taken that would cover
all the cases:

* `composer install --no-dev` would update all the dependencies, which would also require running `drush updatedb` and `drush cr` that could be required in case of updates to Drupal Core or any of the modules.
* `drush cim` would synchronise the configuration from the repository with the current instance. Please note that `drush cron` might be required to rebuild search indexes if any fields were added to or removed from the corresponding configuration.
* `./scripts/import-language <language-code>` to update translations, with possible languages being **et** and **ru**.
  * `./scripts/import-all-languages` would import translations for all available languages.

It would be wise to enable maintenance mode before applying the updates with `drush state:set system.maintenance_mode 1`
and then disabling it once the process is complete with `drush state:set system.maintenance_mode 0`. You can also read the [Updating Drupal core via Composer](https://www.drupal.org/docs/updating-drupal/updating-drupal-core-via-composer#update-all-steps) guide to get a better understanding of the process.

## Development

* Update site settings with `vendor/bin/drush cim`
* Export site settings with `vendor/bin/drush cex`
* Export translations for certain language with `./scripts/export-language.sh <language-code>`
* Export translation for all languages with `./scripts/export-all-languages.sh`

### Update Drupal Core

Make sure to create a backup of the database and files. Then run `./scripts/update-drupal-core.sh`
