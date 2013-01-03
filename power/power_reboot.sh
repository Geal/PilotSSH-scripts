#!/bin/sh
reboot > /dev/null 2>&1
OUT=$?
if [ $OUT -eq 0 ];then
  echo "{ \"version\": 1, \"title\": \"Reboot\", \"type\":\"status\", \"status\":\"ok\", \"message\":\"rebooting the server\"}"
else
  echo "{ \"version\": 1, \"title\": \"Reboot\", \"type\":\"status\", \"status\":\"failed\", \"message\":\"could not reboot the server\"}"
fi
