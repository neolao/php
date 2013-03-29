#!/bin/bash

scriptPath="$( readlink -f "$( dirname "$0" )" )/$( basename "$0" )"
currentDirectory=$(dirname $scriptPath)

cd $currentDirectory/../
vendor/atoum/atoum/bin/atoum
