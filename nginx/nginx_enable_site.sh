#!/bin/sh

if [ "$(id -u)" != "0" ]; then
  echo "{ \"version\": 1, \"title\": \"Enabling $1 site\", \"type\":\"status\", \"status\":\"failed\", \"message\":\"you must be root to do this\"}"
  exit 1
fi

~/.pilotssh/nginx/nginx_ensite $1 > /dev/null 2>&1
nginx -s reload > /dev/null 2>&1
echo "{ \"version\": 1, \"title\": \"Enabling $1 site\", \"type\":\"status\", \"status\":\"ok\", \"message\":\"success\"}"
