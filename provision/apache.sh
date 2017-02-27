#!/bin/bash

echo "Setup apache..."
sudo apt-get install -y apache2

echo "Enable apache..."
sudo systemctl enable apache2
echo "Start apache..."
sudo systemctl start apache2

echo "Enable mod rewrite..."
sudo a2enmod rewrite
sudo apachectl restart

VHOST=$(cat <<EOF
<VirtualHost *:80>
  DocumentRoot "/vagrant/src/www"
  ServerName dev.robot.proj
  ServerAlias dev.robot.proj
  ErrorLog ${APACHE_LOG_DIR}/www-error.log
  CustomLog ${APACHE_LOG_DIR}/www-access.log common
  <Directory "/vagrant/src/www">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Order allow,deny
    Allow from all
    Require all granted
  </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" >> /etc/apache2/sites-enabled/000-default.conf

sudo apachectl restart