#!/bin/bash

up=`uptime`
echo "{ \"version\": 1, \"title\": \"Power\", \"type\":\"commands\", \"values\" : [ { \"name\" : \"Uptime\", \"value\" : \"$up\", \"command\" : \"\"}, {\"name\" : \"Halt\", \"value\" : \"\", \"command\" : \".pilotssh/power/power_halt.sh\" }, {\"name\" : \"Reboot\", \"value\" : \"\", \"command\" : \".pilotssh/power/power_reboot\" } ] }";
