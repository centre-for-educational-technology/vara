# Bootstrap for Drupal TLU sub-theme

Please read instruction of the [parent](https://www.drupal.org/project/bfd) theme if you ever need that.

## Usage

Activate the sub-theme, configure regions, and you are good to go.

## Development

In order to make any changes go to the theme root directory and run `npm install`, which would install all the Node.js
dependencies.

Running `npm run sass` will compile the resulting css file. It might require running `drush cr` to empty caches.

Running `npm run serve` should watch for changes to the `.scss` files.
