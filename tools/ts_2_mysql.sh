#!/usr/bin/env bash


# resets root password to the same pw
read -p "Do you want to reset the root user's password? useful for first install of bitnami (y/n) " RESP
if [ "$RESP" = "y" ]; then
    /opt/bitnami/mysql/bin/mysqladmin -p -u root password "$ts_DB_password"
fi

# creates new DB and user, adds user to DB
mysql -uroot -p"$ts_DB_password" -e "CREATE DATABASE IF NOT EXISTS $ts_DB; \
     GRANT USAGE ON *.* TO $ts_DB_user@localhost IDENTIFIED BY '$ts_DB_password'; \
     GRANT ALL PRIVILEGES ON $ts_DB.* TO $ts_DB_user@localhost; FLUSH PRIVILEGES;"






# create new user

#CREATE USER 'thinkshift'@'localhost' IDENTIFIED BY '$ts_DB_password';
#GRANT ALL PRIVILEGES ON platform . * TO 'thinkshift'@'localhost';
#FLUSH PRIVILEGES;

#sudo /opt/bitnami/mysql -uroot -p"$ts_DB_password" -e "CREATE DATABASE IF NOT EXISTS platfomr; \
#     GRANT ALL PRIVILEGES ON *.* TO root@localhost; FLUSH PRIVILEGES;"

