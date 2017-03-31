#!/bin/bash

cd /var/local
if cd ttp_work; then
    echo -e "\n#########################################################################################################"
    echo -e "Deleting temporary installation files... /var/local/ttp_work/* & /usr/local/bin/ttp_bin/*"
    echo -e "#########################################################################################################\n"
    cd ..; rm -rf ttp_work
    cd /usr/local/bin
    if cd ttp_bin; then
        cd ..; rm -rf ttp_bin
    fi
fi



read -p "Do you want to delete all the files in platform? (y/n) " RESP
if [ "$RESP" = "y" ]; then
    cd /var/www
    if cd wordpress; then
        echo -e "\n#########################################################################################################"
        echo -e "Deleting platform folder/"
        echo -e "#########################################################################################################\n"
        cd ..; rm -rf wordpress
        # delete our custom wordpress.conf file
        rm -f /etc/httpd/conf.d/wordpress.conf
        # delete wp test harness
        rm -rf /opt/wordpress-tests

    fi
fi



read -p "Do you want to delete the mysql tables/database? (y/n) " RESP
if [ "$RESP" = "y" ]; then
    echo -e "\n#########################################################################################################"
    echo -e "Clearing out the database"
    echo -e "#########################################################################################################\n"
    sudo mysql --host=localhost --user="$ttp_DB_user" --password="$ttp_DB_password" $ttp_DB <<EOF
        DROP DATABASE $ttp_DB;
        CREATE DATABASE $ttp_DB;
EOF
fi




read -p "Do you want to uninstall the server dependencies? (y/n) " RESP
if [ "$RESP" = "y" ]; then
    echo -e "\n#########################################################################################################"
    echo -e "Removing installed dependencies"
    echo -e "#########################################################################################################"

    sudo yum -y remove mysql mysql-server
    sudo yum -y remove mysql*
    sudo rm -sudo rf /var/lib/mysql/
    sudo rm -rf /etc/my.cnf

    sudo yum -y remove httpd

    sudo yum -y remove php php-*
    sudo yum -y remove php54w* php54w-*

    # remove wp cli
    sudo rm -f /usr/local/bin/wp

    # todo: reset iptables?
fi



