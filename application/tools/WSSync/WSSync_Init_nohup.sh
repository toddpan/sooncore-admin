#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH

#1. Get current shell script directy
echo "$0" | grep -q "$0"
if [ $? -eq 0 ]; then
        cd "$(dirname "$BASH_SOURCE")"
        CUR_FILE=$(pwd)/$(basename "$BASH_SOURCE")
        CUR_DIR=$(dirname "$CUR_FILE")
        cd - > /dev/null
else
        if [ ${0:0:1} = "/" ]; then
                CUR_FILE=$0
        else
                CUR_FILE=$(pwd)/$0
        fi
        CUR_DIR=$(dirname "$CUR_FILE")
fi
#Remove relative path, etc: a/..b/c
cd "$CUR_DIR"
CUR_DIR=$PWD
cd - > /dev/null
echo $CUR_DIR
echo "$CUR_DIR/WSSync.jar"

#2. Start main job
jarPID=$(ps -eaf | grep WSSync.jar | grep -v grep | awk '{print $2}')
echo ""  >> /tmp/ucadmin_WSSync.txt
echo "*********** Splitter  *********************"  >> /tmp/ucadmin_WSSync.txt
echo "Start UCAdmin Web Service first time init task..."  >> /tmp/ucadmin_WSSync.txt
echo "Current WSSync.jar pid: " $jarPID  >> /tmp/ucadmin_WSSync.txt
if [[ "" == "$jarPID" ]]; then
    echo "WSSync.jar is not running, start." >> /tmp/ucadmin_WSSync.txt
	#java -jar /usr/local/ucadmin/WSSync/WSSync.jar &
	nohup java -jar "$CUR_DIR/WSSync.jar" init &
else
	echo "WSSync.jar is running, so quit." >> /tmp/ucadmin_WSSync.txt
fi
exit
