#!/bin/bash

source ./scripts/config.sh

for language in "${languages[@]}"
do
  ./scripts/export-language.sh "$language"
done

