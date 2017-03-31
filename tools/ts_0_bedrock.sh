#!/usr/bin/env bash

####### this is if for starting a brand new project
####### might be useful later to abstract bedrock out of the repo

sudo apt-get -y install git

# install composer
if [ -e '/usr/local/bin/composer' ] ; then
    echo -e "WP-CLI already installed."
else
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi


cd ../
composer create-project roots/bedrock platform

cd platform/web/app/themes
composer create-project roots/sage thinkshift 8.5.1