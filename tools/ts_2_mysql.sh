#!/usr/bin/env bash


# resets root password to the same pw
/opt/bitnami/mysql/bin/mysqladmin -p -u root password "$ts_DB_password"


# creates new DB and user, adds user to DB
sudo /usr/bin/mysql -uroot -p"$ts_DB_password" -e "CREATE DATABASE IF NOT EXISTS $ts_DB; \
     GRANT USAGE ON *.* TO $ts_DB_user@localhost IDENTIFIED BY '$ts_DB_password'; \
     GRANT ALL PRIVILEGES ON $ts_DB.* TO $ts_DB_user@localhost; FLUSH PRIVILEGES;"






# create new user

#CREATE USER 'thinkshift'@'localhost' IDENTIFIED BY '$ts_DB_password';
#GRANT ALL PRIVILEGES ON platform . * TO 'thinkshift'@'localhost';
#FLUSH PRIVILEGES;

#sudo /opt/bitnami/mysql -uroot -p"$ts_DB_password" -e "CREATE DATABASE IF NOT EXISTS platfomr; \
#     GRANT ALL PRIVILEGES ON *.* TO root@localhost; FLUSH PRIVILEGES;"

