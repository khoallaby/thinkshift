#!/usr/bin/env bash

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
