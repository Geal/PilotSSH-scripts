#!/bin/bash

result="{ \"version\": 1, \"title\": \"Processes\", \"type\":\"commands\", \"values\" : [ "

pids=(/proc/[0-9]*)
pid=`basename ${pids[0]}`
data=`awk '/(Name:|VmRSS)/{print \$2 \$3}' ${pids[0]}/status`
array=($data)
result=$result"{\"name\" : \"${array[0]}\", \"value\":\"${array[1]}  ${array[2]}\", \"command\":\".pilotssh/process/process_info.sh $pid ${array[0]}\"}"

for i in ${pids[*]:1}
do
  pid=`basename $i`
  data=`awk '/(Name|VmRSS)/{print \$2 \$3}' $i/status`
  array=($data)
  result=$result", {\"name\" : \"${array[0]}\", \"value\":\"${array[1]}  ${array[2]}\", \"command\":\".pilotssh/process/process_info.sh $pid ${array[0]}\"}"
done

result=$result"]}"

echo $result
