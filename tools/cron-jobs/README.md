

#### Cron Jobs

Run this command to add them
```
crontab -e
```

Check the system logs for cron
```
sudo zgrep CRON /var/log/syslog*
```



All output is saved to -- /home/bitnami/thinkshift/tools/cron-jobs/cron.log

```
# Update the Tags (daily)
0 0 * * * /opt/bitnami/php/bin/php /home/bitnami/thinkshift/tools/cron-jobs/import-tags.php >> /home/bitnami/thinkshift/tools/cron-jobs/cron.log 

# Low priority - Updates all users (hourly)
0 * * * * /opt/bitnami/php/bin/php /home/bitnami/thinkshift/tools/cron-jobs/update-users.php >> /home/bitnami/thinkshift/tools/cron-jobs/cron.log 

# High priority - Update users with 'update_priority' = 'high' (every minute)
* * * * * /opt/bitnami/php/bin/php /home/bitnami/thinkshift/tools/cron-jobs/update-users.php "high" >> /home/bitnami/thinkshift/tools/cron-jobs/cron.log 
```
