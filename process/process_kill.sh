#!/bin/sh
kill -9 $1 > /dev/null 2>&1
OUT=$?
if [ $OUT -eq 0 ];then
  echo "{ \"version\": 1, \"title\": \"Killing $2\", \"type\":\"status\", \"status\":\"ok\", \"message\":\"success\"}"
else
  echo "{ \"version\": 1, \"title\": \"Killed $2\", \"type\":\"status\", \"status\":\"failed\", \"message\":\"could not kill this process\"}"
fi
