#!/bin/bash

echo "Setup php.."
sudo apt-get install -y php5.6 php5.6-mysql php5.6-curl php5.6-json php5.6-xml php5.6-cgi php5.6-mbstring php5.6-zip libapache2-mod-php5.6 php5.6-intl
sudo a2enmod php5.6
sudo apachectl restart

