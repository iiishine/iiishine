#!/bin/sh
JAVA_HOME=/opt/j2sdk
export JAVA_HOME
JMX_RESULT=$(/opt/jboss/bin/twiddle.sh -o 10.27.23.5 -p 1090 invoke $1 $2)
echo $JMX_RESULT
