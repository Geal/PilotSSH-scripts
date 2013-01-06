#!/bin/bash

echo '{ "version": 1, "title": "Commands", "type":"commands", "values" : [ {"name" : "Apache access", "value" : "", "command" : ".pilotssh/logs/logs_show.sh \"Apache access.log\" apache2/access.log" }, {"name" : "Apache error", "value" : "", "command" : ".pilotssh/logs/logs_show.sh \"Apache error.log\" apache2/error.log" }, {"name" : "Auth", "value" : "", "command" : ".pilotssh/logs/logs_show.sh \"Auth.log\" auth.log" }, {"name" : "Syslog", "value" : "", "command" : ".pilotssh/logs/logs_show.sh \"Syslog\" syslog" } ] }';
