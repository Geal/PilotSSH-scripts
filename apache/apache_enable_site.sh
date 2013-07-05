#!/usr/bin/env sh

if [ "$(id -u)" != "0" ]; then
  echo "{ \"version\": 1, \"title\": \"Enabling $1 site\", \"type\":\"status\", \"status\":\"failed\", \"message\":\"you must be root to do this\"}"
  exit 1
fi

a2ensite $1 > /dev/null 2>&1
apache2ctl graceful > /dev/null 2>&1
echo "{ \"version\": 1, \"title\": \"Enabling $1 site\", \"type\":\"status\", \"status\":\"ok\", \"message\":\"success\"}"
