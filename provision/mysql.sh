#!/bin/bash

echo "Setup mysql selections"
debconf-set-selections <<< 'mysql-server mysql-server/root_password password robot'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password robot'
echo "Setup mysql server"
sudo apt-get install -y mysql-server
echo "Setup mysql client"
sudo apt-get install -y mysql-client
echo "Setup mysql common"
sudo apt-get install -y mysql-common

sudo apt-get install libapache2-mod-auth-mysql php5-mysql

sudo apachectl restart
