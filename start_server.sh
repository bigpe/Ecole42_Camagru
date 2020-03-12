#!/bin/bash

i=0
while [ $i != 5 ]
do
ip=$(ipconfig getifaddr en$i)
if [ -z "$ip" ]
then
	echo "en$i - IP Not Found"
else
	echo "en$i - IP Found"
	php -S $ip:8080
	break
fi
let "i++"
done
