BackupDB
========

```
#!bash

Required:

Create token https://api.ovh.com/createToken/

GET /hosting/web/
GET /hosting/web/*/database
POST /hosting/web/*/database/*/dump
GET /hosting/web/*/database/*/dump
GET /hosting/web/*/database/*/dump/*

composer update -o --no-dev
Edit and rename config.php.dist to config.php file

Optionnal:

Set a cron task
```