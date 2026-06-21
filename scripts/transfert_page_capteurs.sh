#!/bin/bash
# update_capteurs.sh
# This script runs forever and every 30 seconds it does two things:
# 1 - calls the PHP script to read the database and create capteurs.html
# 2 - sends capteurs.html to EoHost via FTP

# My FTP password here
FTP_PASSWORD="Nael.Diallo26?"

echo "Script started, updating every 30 seconds..."

# We loop forever
while true
do
    echo "Generating capteurs.html..."

    # We call the PHP script to read the database and generate the HTML file
    /usr/bin/php /opt/lampp/htdocs/sae23/generate_capteurs.php

    echo "Sending capteurs.html to EoHost..."

    # We send the file to EoHost using curl and FTP
    curl --ftp-create-dirs \
        -T /opt/lampp/htdocs/sae23/capteurs.html \
        ftp://diall0.atwebpages.com/sae23/capteurs.html \
        --user 4687010_youssoufdiallo:$FTP_PASSWORD

    echo "Done! Waiting 30 seconds before next update..."

    # We wait 30 seconds before doing it again
    sleep 30

done
