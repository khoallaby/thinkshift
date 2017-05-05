#!/usr/bin/env bash

sql_file="$1"

if [ -z "$1" ]; then
    sql_file="dev.sql"
else
    if [ $WP_ENV = "development" ] ; then
        sql_file="dev.sql"
    else
        sql_file="prod.sql"
    fi
fi




read -p "(i)mport or (e)xport $sql_file? (i/e) " RESP

if [ "$RESP" = "i" ]; then
    # drop / recreate db / import
    mysql -uroot -p"$ts_DB_password" -e "DROP DATABASE $ts_DB;"
    mysql -uroot -p"$ts_DB_password" -e "CREATE DATABASE $ts_DB;"
    mysql -uroot -p"$ts_DB_password" "$ts_DB" < "$sql_file"

    echo -e "\n#########################################################################################################"
    echo "Imported  $sql_file"
    echo -e "#########################################################################################################"
    read


elif [ "$RESP" = "e" ]; then
    mysqldump -u root -p"$ts_DB_password" "$ts_DB" > "$sql_file"

    echo -e "\n#########################################################################################################"
    echo "Exported  $sql_file"
    echo -e "#########################################################################################################"
    read

fi
