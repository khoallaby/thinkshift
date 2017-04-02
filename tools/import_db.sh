#!/usr/bin/env bash

sql_file="$1"

if [ "$#" -ne 1 ]; then
    sql_file="dev"
fi

mysql -u root -p "$ts_DB" < "$sql_file".sql


echo imported "$sql_file".sql
