#!/usr/bin/env bash


export lamp_dir="/opt/bitnami"



sudo apt-get update
sudo apt-get -y install git





cp tools/conf/bitnami-apps-vhosts.conf "$lamp_dir"/apache2/conf/bitnami/
cp tools/conf/php.ini "$lamp_dir"/php/etc/

# @todo: add cron job
# * * * * * /opt/bitnami/php/bin/php /home/bitnami/thinkshift/tools/cron-jobs/infusionsoft.php
# 0 * * * * /opt/bitnami/php/bin/php /home/bitnami/thinkshift/tools/cron-jobs/import-tags.php




# install everything in the directory above
#cd ~/
cd ..

# @todo: add staging/dev servers



git clone https://github.com/thinkshift/tsdevserver.com.git andy-thinkshift
git checkout dev

cd andy-thinkshift/tools
chmod +x *.sh
bash install.sh


cd ../..


git clone https://github.com/thinkshift/tsdevserver.com.git marty-thinkshift
git checkout dev

cd marty-thinkshift/tools
chmod +x *.sh
bash install.sh


# @todo: modify .env file after each install

