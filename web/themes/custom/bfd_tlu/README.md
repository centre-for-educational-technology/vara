# Bootstrap for Drupal TLU sub-theme

Please read instruction of the [parent](https://www.drupal.org/project/bfd) theme if you ever need that.

## Usage

Activate the sub-theme, configure regions, and you are good to go.

## Development

In order to make any changes go to the theme root directory and run `npm install`, which would install all the Node.js
dependencies.

Tasks:

* `npm run build` will compile the resulting css file. It might require running `drush cr` to empty caches.
* `npm run watch` will watch for changes to the `.scss` files and rebuild on each change.
* `npm run serve` will use browser-sync and serve your web, making a refresh on each rebuild.
  * `npm config set url <URL>` will set the URL to be served through browser-sync.
