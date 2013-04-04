#!/bin/bash

scriptPath="$( readlink -f "$( dirname "$0" )" )/$( basename "$0" )"
currentDirectory=$(dirname $scriptPath)

#php $currentDirectory/../vendor/sami/sami/sami.php update $currentDirectory/documentation/sami-config.php -v
$currentDirectory/../vendor/apigen/apigen/apigen.php --source $currentDirectory/../classes/ --destination $currentDirectory/../documentation/

