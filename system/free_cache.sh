#!/bin/bash

sync
echo 3 > /proc/sys/vm/drop_caches
swapoff -a 2>/dev/null
swapon -a 2>/dev/null

echo "{ \"version\": 1, \"title\": \"Freeing cache\", \"type\":\"status\", \"status\":\"ok\", \"message\":\"success\"}"
