#!/bin/sh
halt > /dev/null 2>&1
OUT=$?
if [ $OUT -eq 0 ];then
  echo '{ "version": 1, "title": "Halt", "type":"status", "status":"ok", "message":"The server will halt"}'
else
  echo '{ "version": 1, "title": "Halt", "type":"status", "status":"failed", "message":"could not halt the server"}'
fi
