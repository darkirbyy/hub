CREATE DATABASE IF NOT EXISTS hub_db;
CREATE DATABASE IF NOT EXISTS hub_db_test;

CREATE USER  IF NOT EXISTS 'hub_user'@'%' IDENTIFIED BY 'hub_password';

GRANT ALL PRIVILEGES ON hub_db.* TO 'hub_user'@'%';
GRANT ALL PRIVILEGES ON hub_db_test.* TO 'hub_user'@'%';