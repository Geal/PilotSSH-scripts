# Scripting API

This document contains the current API for the scripts of PilotSSH. It defines a simple JSON interface that will not depend on the scripting language. The commands presented here all use Bash, but Perl, Python, Ruby, anything you can think of should be used.

## Bootstrap

The first script called by PilotSSH is `.pilotssh/index`. This script follows the same API as other scripts, and is commonly used to find other commands.

## API responses

The application calls scripts, and will parse the JSON output to generate its user interface. Here is the format of the result:

```json
{
  "version" : 1,
  "title"   : "Index",
  "type"    : "commands",
  "status"  : "",
  "message" : "",
  "values"  : [
    {
      "name" : "logs",
      "value" : "",
      "command" : ".pilotssh/logs.sh"
    }
  ],
  "text": ""
}
```

* *version*: set to 1 for now
* *title*: defines the page's title in the application, or the alertbox's title (for status messages)
* *type*: defines the type of answer, and the way the application will handle it. It can take three values: **commands**, **status** and **text**
* *status*: defines the type of status message. It can take two values: **failed** and **ok**
* *message*: defines the message displayed in the alertbox
* *values*: an array of values used to generate the rows in the interface
  * *name*: the title of the row, displayed on the left of the row
  * *value*: a subtitle, displayed on the right of the row
  * *command*: the command that will be launched if the row is touched by the user
* *text*: define a large block of text that will be displayed

## Response types

### Commands


### Status


### Text

