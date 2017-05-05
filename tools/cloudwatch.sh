#!/usr/bin/env bash

# install this is in user folder ~/

sudo apt-get update
sudo apt-get -y install unzip
sudo apt-get -y install libwww-perl libdatetime-perl

cd ~/

curl http://aws-cloudwatch.s3.amazonaws.com/downloads/CloudWatchMonitoringScripts-1.2.1.zip -O

unzip CloudWatchMonitoringScripts-1.2.1.zip
rm CloudWatchMonitoringScripts-1.2.1.zip
cd aws-scripts-mon


cp awscreds.template awscreds.conf
# nano awscreds.conf


# add the cronjob below
# */5 * * * * ~/aws-scripts-mon/mon-put-instance-data.pl --mem-util --disk-space-util --disk-path=/ --from-cron