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
export ts_DB="thinkshift"
export ts_DB_user="thinkshift"
export ts_DB_password="tH1nk~_sh1ft@20141188sdfF"


while true; do
    clear
    echo "
    1   Install wp/project files
    2   Install mysql DB, user
    3   Git pull/Update project
    4   Restart services
    5   Import DB
    ( Delete everything (todo) )
    6   Quit
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
            sudo /opt/bitnami/ctlscript.sh restart apache
            # sudo /opt/bitnami/ctlscript.sh restart mysql
            read
            ;;
        5)
            bash import_db.sh
            echo -e "\n#########################################################################################################"
            echo "sql imported"
            echo -e "#########################################################################################################"
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




