#!/usr/bin/env bash


cd ..
# hard reset on staging servers
# git reset --hard origin/master
# deletes all untracked files as well
# git clean -fd
git pull
cd tools
chmod +x *.sh

cd ../platform
composer update
cd web/app/themes/thinkshift
composer update
yarn run build
if [ $WP_ENV = "production" ] ; then
    gulp --production
#else
#    yarn run build
#fi

cd ../../plugins/think-shift
composer update
