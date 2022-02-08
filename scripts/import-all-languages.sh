#!/bin/bash

source ./scripts/config.sh

for language in "${languages[@]}"
do
  ./scripts/import-language.sh "$language"
done
