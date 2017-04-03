#!/usr/bin/env bash


cd ..
# hard reset on staging servers
# git reset --hard origin/master
git pull
cd tools
chmod +x *.sh

cd ../platform
composer update
cd web/app/themes/thinkshift
composer update
yarn run build

cd ../../plugins/think-shift
composer update
