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

* *version*: set to 1 for now. It must be present in every response
* *title*: defines the page's title in the application, or the alertbox's title (for status messages). It must be present in every response
* *type*: defines the type of answer, and the way the application will handle it. It can take three values: **commands**, **status** and **text**. It must be present in every response
* *status*: defines the type of status message. It can take two values: **failed** and **ok**. It must be present for the 'status' responses
* *message*: defines the message displayed in the alertbox. It must be present for the 'status' responses
* *values*: an array of values used to generate the rows in the interface. It must be present for the 'commands' responses
  * *name*: the title of the row, displayed on the left of the row
  * *value*: a subtitle, displayed on the right of the row
  * *command*: the command that will be launched if the row is touched by the user
* *text*: define a large block of text that will be displayed. It must be present for the 'text' responses

## Response types

### Commands

The application will create a table with a title defined by the 'title' attribute, and whose rows will be defined by the 'values' attribute. Touching one of these rows will launch the command. the command launched might not be a script stored in `.pilotssh`: any other script path (or script that can be found on the PATH) can be used, as long as it echoes a JSON text compatible with this API.

### Status

The application will display an alertbox, with the title defined by the 'title' attribute, and the text defined by the 'message' attribute. The 'status' attribute will be used in a future version of the application.

### Text

The application will display a large text zone, with the title defined by the 'title' attribute, and the content defined by the 'text' attribute.

