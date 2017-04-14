#!/bin/bash
ts_dir="$1"

if [ "$#" -ne 1 ]; then
    ts_dir="thinkshift"
fi

export ts_dir

# todo add branch later
#ts_branch="$2"
#if [ "$#" -ne 1 ]; then
#    ts_branch="master"
#fi

#export ts_branch



# Database Information
# todo: pull from .env
export lamp_dir="/opt/bitnami"


export ts_DB="thinkshift"
export ts_DB_user="thinkshift"
export ts_DB_password="tH1nk~_sh1ft@20141188sdfF"


while true; do
    clear
    echo "
    [initial installation process]
    ------------------------------------------
    1   Install wp/project files
    2   Install mysql DB, user
    ------------------------------------------
    3   Git pull/Update project
    ------------------------------------------
    4   Restart services
    5   Import/Export DB
    6   Log into MySQL
    ------------------------------------------
    7   Run all cron jobs (once)
    _   ( Delete everything (todo) )

    0   Quit

    "
    read INPUT

    case $INPUT in
        1)

            bash ts_1_setup.sh
            #./ts_4_finalize.sh

            echo -e "\n#########################################################################################################"
            echo "Everything's installed"
            echo -e "#########################################################################################################"
            read
            ;;
        2)
            bash ts_2_mysql.sh
            echo -e "\n#########################################################################################################"
            echo "Mysql DB, users installed"
            echo -e "#########################################################################################################"
            read
            ;;
        3)
            bash ts_3_update.sh
            echo -e "\n#########################################################################################################"
            echo "Project updated"
            echo -e "#########################################################################################################"
            read
            ;;
        4)
            # todo: make more generic
            sudo bash services.sh restart apache
            sudo bash services.sh restart php-fpm
            sudo bash services.sh restart mysql
            read
            ;;
        5)

            read -p "File name to use? (defaults to dev.sql)" RESP
            bash import_db.sh "$RESP"
            ;;
        6)
            mysql -u root -p"$ts_DB_password" "$ts_DB"
            read
            ;;
        7)
            php cron-jobs/run-all.php
            read
            ;;
        9)
            bash ts_delete.sh
            echo -e "\n#########################################################################################################"
            echo "Everything's deleted"
            echo -e "#########################################################################################################"
            read
            ;;
        *)
            exit 0
            ;;
    esac
done



