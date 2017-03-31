#!/usr/bin/env bash

sudo apt-get update


# git
sudo apt-get -y install git


# Install wp-cli as 'wp'
if [ -e '/usr/local/bin/wp' ] ; then
    echo -e "WP-CLI already installed."
else
    sudo curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    sudo chmod +x wp-cli.phar
    sudo mv wp-cli.phar /usr/local/bin/wp
fi


# install composer
if [ -e '/usr/local/bin/composer' ] ; then
    echo -e "WP-CLI already installed."
else
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi




# install node, npm
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
sudo apt-get -y install nodejs
sudo apt-get -y install npm

# install bower, gulp
sudo npm install -g bower
sudo npm install --global gulp-cli





# install yarn
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list

sudo apt-get update && sudo apt-get -y install yarn




# clear out htdocs
#bash ts_bedrock.sh
# cd ~/
# rm -rf htdocs/*



# we need to run composer install inside both bedrock and sage directories to install dependencies

cd ../platform
composer install


# run yarn, and build assets
cd web/app/themes/thinkshift
composer install
yarn
yarn run build





