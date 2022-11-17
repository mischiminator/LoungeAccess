Client Access Code Service for use with NUKI V3 and LOX24 SMS Gateway

This tool is used to grant access to clients at our 24/7 Tesla Charger Lounge.
It keeps track of access codes as well as recuring customers.


INSTALLATION:

- create database and user
- import db.sql to newly created database
- Copy include/config.example.php to include/config.php
- Edit config.php
    - Enter API Tokens
    - Enter reCaptcha Credentials (only v2 supported)
    - Enter DB Credentials
    - Enter logofile name
    - Enter timezone 
- Add CronJob ("crontab -e")
    - change to desired timeframe
    "0 * * * * /path/to/php path/to/include/cron.php # AccessToken tool cleanup every hour" 