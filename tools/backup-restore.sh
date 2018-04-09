#!/usr/bin/env bash

# https://docs.bitnami.com/aws/apps/wordpress/#how-to-create-a-full-backup-of-wordpress

sudo /opt/bitnami/ctlscript.sh stop
sudo tar -pczvf application-backup.tar.gz /opt/bitnami
sudo /opt/bitnami/ctlscript.sh start
