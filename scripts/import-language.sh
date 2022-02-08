#!/bin/bash

./vendor/bin/drush locale-import --override=none "$1" "$(pwd)/config/locales/$1.po"
