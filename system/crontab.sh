#!/bin/bash

if [[ "$1" == "new" ]]
then
  tab=`crontab -l`
  entry="${@:2}"
  echo "$tab"$'\n'"$entry" > ~/.pilotssh/system/test_crontab
  crontab ~/.pilotssh/system/test_crontab
  res=$?
  rm ~/.pilotssh/system/test_crontab
  if [ $res == 0 ]
  then
    echo '{ "version": 1, "title": "New cron added", "type":"status", "status":"ok", "message":"'"$entry"'" }'
  else
    echo '{ "version": 1, "title": "Error adding cron", "type":"status", "status":"error", "message":"'"$entry"'" }'
  fi
elif [[ "$1" == "edit" ]]
then
  IFS=$'\n'$'\r'
  lines=( $(crontab -l) );
  result=""
  ignore_reg="^#"
  res=0
  index=0
  entry="${@:3}"
  for line in "${lines[@]}"
  do
    if [[  "$line" =~ $ignore_reg ]]
    then
      result=$result$'\n'"$line"
    else
      if [[ $2 = $index ]]
      then
        result=$result$'\n'"$entry"
      else
        result=$result$'\n'"$line"
      fi
      ((index++))
    fi
  done
  echo "$result" > ~/.pilotssh/system/test_crontab
  crontab ~/.pilotssh/system/test_crontab
  res=$?
  rm ~/.pilotssh/system/test_crontab
  if [ $res == 0 ]
  then
    echo '{ "version": 1, "title": "Cron modified", "type":"status", "status":"ok", "message":"'"$entry"'"}'
  else
    echo '{ "version": 1, "title": "Error modifying cron", "type":"status", "status":"error", "message":"'"$entry"'"}'
  fi
else
  echo '{ "version": 1, "title": "Crontab", "type":"commands", "values" : [ { "name":"Add new entry", "value":"", "command":".pilotssh/system/crontab.sh new", "query":"add:\nm h dom mon dow command"}'
  IFS=$'\n'$'\r'
  lines=( $(crontab -l) );
  if [ $? != 0 ]
  then
    echo ', { "name" : "No cron entries", "value" : "", "command" : ""}';
  else
    ignore_reg="^#"
    res=0
    index=0
    for line in "${lines[@]}"; do
      if [[ ! "$line" =~ $ignore_reg ]]
      then
        echo ', {"name":"' $line '","value" : "", "command" : ".pilotssh/system/crontab.sh edit ' $index '", "query":"edit:\nm h dom mon dow command"}'
        res=1      
        ((index++))
      fi
    done
    if [ $res == 0 ]
    then
      echo ', { "name" : "No cron entries", "value" : "", "command" : ""}'
    fi
  fi
  echo ']}' 
fi
