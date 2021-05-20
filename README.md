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
  * Configure `config/authsources.php` file to include an authentication source name `tlu-h5p-sp`
  * Make sure to set `privatekey` and `certificate` to the generated files, which are placed into the `cert` directory
  * `idp` value should be set to the one that you add into the `metadata` directory and prevent user from having to choose the idp from the list of available ones, thus making the whole process as smooth as possible
   * Most probable value would be `https://passwd.tlu.ee/sisedevauth/saml2/idp/metadata.php`
* Suggestions for running auth service on a different subdomain
  * Make sure that `baseurlpath` is set to the fully qualified URL of local auth Service Provider (SP) service
  * Setting for `trusted.url.domains` should include both TLU Identity Provider service domain and Drupal instance domain
  * Be sure to set `database.dsn` or any other setting so that default session would not be used for auth data storage
  * `session.cookie.domain` should be set to `.<main-domain>` so that both Drupal and SP service would be covered
* Required attributes are: sn, cn, uid, givenName, mail, displayname, preferredLanguage
  * `uid` is used for user unique identifier and `mail` as an email address
  * Other attributes will be used if provided with checks applied

Other details would be provided from the TLU IT department side along with metadata settings and configuration suggestions.

## Development

* Update site settings with `vendor/bin/drush cim`
* Export site settings with `vendor/bin/drush cex`
* Export menus with `vendor/bin/drush em` or use a UI for that. Standard settings export command will be required afterwards.
* Export Estonian translations with `/vendor/bin/drush locale:export et > <full-path-to-drupal>/config/locales/et.po`
