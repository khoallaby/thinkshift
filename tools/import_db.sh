#!/usr/bin/env bash

sql_file="$1"

if [ "$#" -ne 1 ]; then
    sql_file="dev"
fi

read -p "(i)mport or e(xport) $sql_file.sql? (i/e) " RESP

if [ "$RESP" = "i" ]; then
    # drop / recreate db / import
    mysql -uroot -p"$ts_DB_password" -e "DROP DATABASE $ts_DB;"
    mysql -uroot -p"$ts_DB_password" -e "CREATE DATABASE $ts_DB;"
    mysql -uroot -p"$ts_DB_password" "$ts_DB" < "$sql_file".sql

    echo imported "$sql_file".sql


elif [ "$RESP" = "e" ]; then
    mysqldump -u root -p"$ts_DB_password" "$ts_DB" > "$sql_file".sql

    echo exported  "$sql_file".sql

fi
