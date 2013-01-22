# Scripts for PilotSSH

This repository holds scripts usable with the [PilotSSH](http://pilotssh.com) application, available on [iOS](https://itunes.apple.com/us/app/pilot-ssh/id591678815?l=fr&ls=1&mt=8) and soon on Android. This application provides an easy way to manage a server, through a set of scripts stored on the server, used to generate a convenient user interface on your smartphone.

To use a new script, upload its folder to **~/.pilotssh** on your server, update the **.pilotssh/index** script to point to the new folder, and you are ready to go!

# Screenshots

Here are some pictures of scripts usable with Pilot SSH:

![WordPress Administration](https://raw.github.com/Geal/PilotSSH-scripts/master/img/wordpress.png)

![Process list](https://raw.github.com/Geal/PilotSSH-scripts/master/img/process_list.png)

# Building your own scripts

Take inspiration from the scripts stored here to build your own. You can use any language you want! The **index¨** script is the first one called by Pilot SSH, and displays a list of available scripts:

![Index](https://raw.github.com/Geal/PilotSSH-scripts/master/img/index.png)

* The "Process list" script shows how to use alert boxes in the application.
* The "Power" script displays the two different ways to display a cell (with and without a command, with and without a value)
* The "Logs" script shows how to display a large block of text
* The "Echo" script demonstrates how to ask some input from the user

The WordPress administration script builds on all the previous one to show a complete example of what can be done. The most interesting part is the configuration one: the script asks the user for the path to the WordPress website, and generates its configuration.

Almost anything can be done with Pilot SSH, so be creative! Happy Hacking!
