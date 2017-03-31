#!/usr/bin/env bash


cd ../
git pull
cd platform
composer update
cd web/app/themes/thinkshift
composer update
yarn run build