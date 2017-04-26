#!/usr/bin/env bash

export theme_dir="platform/web/app/themes/thinkshift"
export plugin_dir="platform/web/app/themes/think-shift"


while true; do
    clear
    echo "
    ------------------------------------------
    1   gulp && gulp watch
    2   (todo) cd to theme dir - $theme_dir
    3   (todo) cd to plugin dir - $plugin_dir
    ------------------------------------------
    0   Quit

    "
    read INPUT

    case $INPUT in
        1)
            cd "$theme_dir"
            gulp && gulp watch
            ;;
        2)
            cd "$theme_dir"
            ;;
        3)
            cd "$plugin_dir"
            ;;
        *)
            exit 0
            ;;
    esac
done



