#!/usr/bin/python
import netifaces, sys

def command_from_interface(intf):
    return '{ "name" : "' + intf + '", "value" : "'+ netifaces.ifaddresses(intf)[netifaces.AF_INET][0]["addr"] + '", "command" : ".pilotssh/network/network.py ' + intf + '" }'

def index():
    result = '{ "version": 1, "title": "Network Interfaces", "type":"commands", "values" : [ '
    interfaces = netifaces.interfaces()
    length = len(interfaces)

    if(length >= 1):
        result += command_from_interface(interfaces[0])

    if(length > 1):
        for i in xrange(1, length):
            result += ', ' + command_from_interface(interfaces[i])

    result += ' ] }'

    print result

def command_from_key_value(key, value):
    return '{ "name" : "' + key + '", "value" : "'+ value + '", "command" : "" }'

def interface_info(intf):
    result = '{ "version": 1, "title": "' + intf + '", "type":"commands", "values" : [ '

    af_inet = netifaces.ifaddresses(intf)[netifaces.AF_INET][0]
    address = af_inet["addr"]
    result += command_from_key_value("IP", address)

    if "broadcast" in af_inet:
        broadcast = af_inet["broadcast"]
        result += ', ' + command_from_key_value("Broadcast", broadcast)

    netmask = af_inet["netmask"]
    result += ', ' + command_from_key_value("Netmask", netmask)

    mac = netifaces.ifaddresses(intf)[netifaces.AF_LINK][0]["addr"]
    result += ', ' + command_from_key_value("MAC", mac)

    result += ' ] }'
    return result


if(len(sys.argv) == 1):
    index()
else:
    print interface_info(sys.argv[1])
