#!/bin/bash

ta=`tail /var/log/apache2/access.log`
t4=`echo $ta | base64 -w 0`
echo "{ \"version\": 1, \"title\": \"Apache access.log\", \"type\":\"text\", \"status\":\"ok\", \"text\":\"$t4\"}"
