#!/bin/bash

# startup.sh
# This script automatically starts all the services of the SAE23 project
# when the VM boots up, thanks to an @reboot line in crontab.

# We wait a bit so Docker has time to start before launching the containers
sleep 30

# Start the Docker containers (Mosquitto, InfluxDB, Node-RED, Grafana)
docker start mosquittoRT
docker start influxdbRT
docker start noderedRT
docker start grafanaRT

# We wait a bit before starting the LAMPP server
sleep 10

# Start the LAMPP server for the website
sudo /opt/lampp/lampp start

echo "All services were started on $(date)" >> /home/diallo/logs/startup.log
