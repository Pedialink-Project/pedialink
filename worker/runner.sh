#!/bin/sh
set -eu

# trap SIGTERM and SIGINT for graceful exit
trap 'echo "[$(date)] received stop signal, exiting"; exit 0' TERM INT

# run forever: run worker, sleep 30s
while true; do
  echo "[$(date)] invoking vaccination.php"
  php /var/www/html/worker/vaccination.php || echo "[$(date)] worker failed"
  sleep 30
done