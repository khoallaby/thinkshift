#!/usr/bin/env bash

sudo apt-get update


# git
sudo apt-get -y install git
# git st, ci aliases
git config --global alias.st status
git config --global alias.ci 'commit -v'
git config --global push.default simple



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




# install node, npm, php-xdebug
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
sudo apt-get -y install nodejs
sudo apt-get -y install npm
sudo apt-get -y install php5-xdebug


# install bower, gulp
sudo npm install -g bower
sudo npm install --global gulp-cli





# install yarn
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list

sudo apt-get update && sudo apt-get -y install yarn



if [ $WP_ENV = "production" ] ; then
    vhost_file = "conf/bitnami-apps-vhosts-prod.conf"
else
    vhost_file = "conf/bitnami-apps-vhosts.conf"
fi

cp $vhost_file "$lamp_dir"/apache2/conf/bitnami/
cp conf/php.ini "$lamp_dir"/php/etc/


# clear out htdocs
#bash ts_bedrock.sh
# cd ~/
# rm -rf htdocs/*



# we need to run composer install inside both bedrock and sage directories to install dependencies

cd ../platform
composer install
#ignore our .env file, even if changed.
git update-index --assume-unchanged .env
#cp .env.copy .env

# permissions - allow write access to uploads/plugins
sudo chown -v -R bitnami:daemon web/app/uploads
#sudo chmod -v -R 755 web/app/uploads
#sudo chown -v -R bitnami:daemon web/app/plugins
#sudo chmod -v -R 755 web/app/plugins
#sudo find web/ -type f -exec chmod 664 {} \;
#sudo find web/ -type d -exec chmod 775 {} \;


# install wordpress
# @todo: remove this later when figure out how bedrock installs WP
cd web/wp
composer install
mv wordpress/* ./
rm -rf wordpress
cd ../..



# run yarn, and build assets
cd web/app/themes/thinkshift
composer install
yarn
yarn run build
if [ $WP_ENV = "production" ] ; then
    gulp -production




cd ../../plugins/think-shift
composer install


