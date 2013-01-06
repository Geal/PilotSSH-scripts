#!/bin/bash

ta=`tail -n 100 /var/log/$2`
t4=`echo "$ta" | base64 -w 0`
echo "{ \"version\": 1, \"title\": \"$1\", \"type\":\"text\", \"status\":\"ok\", \"text\":\"$t4\"}"
