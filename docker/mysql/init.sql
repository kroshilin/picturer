CREATE USER 'telepoll'@'localhost' IDENTIFIED WITH mysql_native_password BY 'telepoll';
GRANT ALL PRIVILEGES ON *.* TO 'telepoll'@'localhost' WITH GRANT OPTION;
CREATE USER 'telepoll'@'%' IDENTIFIED WITH mysql_native_password BY 'telepoll';
GRANT ALL PRIVILEGES ON *.* TO 'telepoll'@'%' WITH GRANT OPTION;
CREATE DATABASE IF NOT EXISTS telepoll CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL ON `telepoll`.* TO 'telepoll'@'%';
FLUSH PRIVILEGES;