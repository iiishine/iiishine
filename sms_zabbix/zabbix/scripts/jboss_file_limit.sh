#!/bin/sh
JBOSS_PID=$(ps -Ao pid,command | grep java | grep jboss | awk '{print $1}')
#echo $JBOSS_PID
if [ -z "$PID"]; then
JBOSS_FILE_LIMIT=$(cat /proc/$JBOSS_PID/limits | grep "open files" | awk '{print $4}')
echo $JBOSS_FILE_LIMIT
else
echo '-1' 
fi
