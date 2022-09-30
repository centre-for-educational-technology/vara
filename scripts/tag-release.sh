#!/bin/bash

char_n_times() {
  tmp=""
  for ((i=0; i<$2; i++)); do tmp+=$1; done
  echo $tmp
}

print_header() {
  n=${#1}
  echo $(char_n_times "#" n+4)
  echo "# $1 #"
  echo $(char_n_times "#" n+4)
}

if [ $# -lt 1 ]
then
  echo "Tag name should be passed as an argument!"
  exit 1
fi

MAIN_BRANCH="main"
PRODUCTION_BRANCH="production"
current_branch=$(git branch --show-current)
tag_name="$1"

print_header "Beginning the process of tagging a new release"

echo "Checking out production branch \"${PRODUCTION_BRANCH}\""
git checkout -q $PRODUCTION_BRANCH

echo "Merging \"${MAIN_BRANCH}\" into \"${PRODUCTION_BRANCH}\""
# TODO See if we should handle any possible merge issues
git merge -q $MAIN_BRANCH
git push origin $PRODUCTION_BRANCH

echo "Tagging a new release \"${tag_name}\" and pushing it to origin"
git tag $tag_name || { echo "Unable to create the tag \"${tag_name}\"!"; git checkout -q $MAIN_BRANCH; exit 1; }
git push origin $tag_name

echo "Reverting to branch \"${current_branch}\""
git checkout -q $MAIN_BRANCH

exit 0
