#!/usr/bin/env sh

result="{ \"version\": 1, \"title\": \"$2\", \"type\":\"commands\", \"values\" : [ "

pid=$1
name=$2
#cwd=`readlink /proc/$pid/cwd`
cmdline=`ps eho command -p $pid`
cpu=`ps eho %cpu -p $pid`
mem=`ps eho %mem -p $pid`

result=$result"{\"name\" : \"PID\", \"value\":\"$pid\", \"command\":\"\"},"
#result=$result"{\"name\" : \"CWD\", \"value\":\"$cwd\", \"command\":\"\"},"
result=$result"{\"name\" : \"CMD\", \"value\":\"$cmdline\", \"command\":\"\"},"
result=$result"{\"name\" : \"CPU\", \"value\":\"$cpu\", \"command\":\"\"},"
result=$result"{\"name\" : \"MEM\", \"value\":\"$mem\", \"command\":\"\"},"
result=$result"{\"name\" : \"Kill -9\", \"value\":\"\", \"command\":\".pilotssh/process/process_kill.sh $pid $name\"}"
result=$result" ] }"

echo $result
