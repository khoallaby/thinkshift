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

#
# Settings in the following section should only be changed if required for production.
#
export PATH=/usr/local/bin/ts_bin:/usr/local/bin:$PATH

# Database Information
export ts_DB="thinkshift"
export ts_DB_user="thinkshift"
export ts_DB_password="tH1nk~_sh1ft@20141188sdfF"


while true; do
    clear
    echo "
    1   Prep & Install
    2   Install mysql DB, user
    3   Delete everything
    4   Restart services
    5   Quit
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
            bash ts_delete.sh
            echo -e "\n#########################################################################################################"
            echo "Everything's deleted"
            echo -e "#########################################################################################################"
            read
            ;;
        4)
            sudo /opt/bitnami/ctlscript.sh restart apache
            # sudo /opt/bitnami/ctlscript.sh restart mysql
            read
            ;;
        *)
            exit 0
            ;;
    esac
done




