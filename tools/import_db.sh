#!/usr/bin/env bash

sql_file="$1"

if [ "$#" -ne 1 ]; then
    sql_file="dev"
fi

# drop / recreate db / import
mysql -uroot -p"$ts_DB_password" -e "DROP DATABASE $ts_DB;"
mysql -uroot -p"$ts_DB_password" -e "CREATE DATABASE $ts_DB;"
mysql -uroot -p"$ts_DB_password" "$ts_DB" < "$sql_file".sql


echo imported "$sql_file".sql
