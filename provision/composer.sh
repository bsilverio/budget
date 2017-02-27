#!/bin/bash

echo "Download composer.."
sudo mkdir -p /vagrant/installers
cd /vagrant/installers
php -r "copy('https://getcomposer.org/installer','composer-setup.php');"
echo "Install composer.."
php composer-setup.php --install-dir=/vagrant/installers --filename=composer > /dev/null
echo "Move composer for global access.."
sudo mv composer /usr/local/bin/composer
echo "Delete setup file for composer.."
php -r "unlink('composer-setup.php');"