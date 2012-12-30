#!/bin/sh

if [ "$(id -u)" != "0" ]; then
  echo "{ \"version\": 1, \"title\": \"Disabling $1 site\", \"type\":\"status\", \"status\":\"failed\", \"message\":\"you must be root to do this\"}"
  exit 1
fi

a2dissite $1 > /dev/null 2>&1
apache2ctl graceful > /dev/null 2>&1
echo "{ \"version\": 1, \"title\": \"Disabling $1 site\", \"type\":\"status\", \"status\":\"ok\", \"message\":\"success\"}"
