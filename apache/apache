#!/bin/bash
available=(/etc/apache2/sites-available/*)
parsedAvailable=()
enabled=(/etc/apache2/sites-enabled/*)
parsedEnabled=()
for i in ${available[*]}
do
  file=`basename $i`
  parsedAvailable+=($file)
done

#echo "parse available"
#for i in ${enabled[*]}
#do
#  echo $i
#done

if [ "$(ls -A /etc/apache2/sites-enabled)" ]
then
#echo "enabled NOT EMPTY"
#echo ${#enabled[*]}
for i in ${enabled[*]}
do
  #echo $i
  path=`readlink -f $i`
  file=`basename $path`
  #echo $file
  for ((i = 0; i < ${#parsedAvailable[*]}; i++)); do
    #echo "Element $i: '${parsedAvailable[i]}'"
    if [ $file == ${parsedAvailable[i]} ]
    then
      #echo "present in available"
      unset parsedAvailable[$i]
    fi
  done
  parsedEnabled+=($file)
done
fi
#echo "parse available"
#for i in ${parsedAvailable[*]}
#do
#  echo $i
#done

result="{ \"version\": 1, \"title\": \"Apache services\", \"type\":\"commands\", \"values\" : ["

if [ ${#parsedEnabled[*]} -gt 0 ]
then
  result=$result"{\"name\" : \"${parsedEnabled[0]}\", \"value\":\"enabled\", \"command\":\".pilotssh/apache/apache_remove_site.sh ${parsedEnabled[0]}\"}"
fi
for ((i=1; i<${#parsedEnabled[*]}; i++)); do
  result=$result", {\"name\" : \"${parsedEnabled[i]}\", \"value\":\"enabled\", \"command\":\".pilotssh/apache/apache_remove_site.sh ${parsedEnabled[i]}\"}"
done

filteredAvailable=( "${parsedAvailable[@]}" )

first=0
if [ ${#parsedEnabled[*]} -eq 0 ]
then
  result=$result"{\"name\" : \"${filteredAvailable[0]}\", \"value\":\"disabled\", \"command\":\".pilotssh/apache/apache_enable_site.sh ${filteredAvailable[0]}\"}"
  first=1
fi
for ((i=first; i<${#filteredAvailable[*]}; i++)); do
  result=$result", {\"name\" : \"${filteredAvailable[i]}\", \"value\":\"disabled\", \"command\":\".pilotssh/apache/apache_enable_site.sh ${filteredAvailable[i]}\"}"
done

result=$result"]}"

echo $result

