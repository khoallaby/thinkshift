#!/usr/bin/env bash


sudo apt-get update
sudo apt-get -y install git



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