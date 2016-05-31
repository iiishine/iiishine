#!/bin/bash
source /etc/profile
source /etc/bashrc
route_test=0

route_test=`route | grep -v Destination | grep -v default |grep -v 10.27.22.0 | grep -v Kernel | wc -l`

if [ $route_test -lt 5 ]; then
  echo '0'
else 
  echo '1' 
fi

