#!/bin/bash

./vendor/bin/drush locale:export "$1" > "$(pwd)/config/locales/$1.po"
