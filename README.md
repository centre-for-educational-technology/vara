# TLU H5P

## Requirements

* PHP version 7.4
* MySQL version 5.7+

## Installation

* `composer install`
* Setup Drupal site with language set to Estonian
* Make sure that configuration synchronisation directory is set to `$settings['config_sync_directory'] = '../config/sync';`
* Override SimpleSAMLphp location unless you are using the default one from vendor `$settings['simplesamlphp_dir'] = '<full-path-to-simplesamlphp>';`
  * Please note that local authentication service provider has to be configured with the TLU identity provider named `tlu-h5p-sp`
* Set site identifier to match the one for synchronised data by running `vendor/bin/drush cset system.site uuid <uuid> -y`
  * You can get site unique identifier [here](https://github.com/centre-for-educational-technology/tlu-h5p/blob/main/config/sync/system.site.yml)
* Import configuration data with `vendor/bin/drush cim -y`
  * Import structure data is provided by [Structure Sync](https://www.drupal.org/project/structure_sync) module. Run `vendor/bin/drush im` to import menus.
  * Import estonian locale with `vendor/bin/drush locale-import --override=none et <full-path-to-drupal>/config/locales/et.po`

### SimpleSAMLphp configuration

* Authentication source for name should be set to `tlu-h5p-sp`
* Required attributes are: sn, cn, uid, givenName, mail, displayname, preferredLanguage
  * `uid` is used for user unique identifier and `mail` as an email address
  * Other attributes will be used if provided with checks applied

## Development

* Update site settings with `vendor/bin/drush cim`
* Export site settings with `vendor/bin/drush cex`
* Export menus with `vendor/bin/drush em` or use a UI for that. Standard settings export command will be required afterwards.
* Export Estonian translations with `/vendor/bin/drush locale:export et > <full-path-to-drupal>/config/locales/et.po`
