#!/bin/bash

export DEBIAN_FRONTEND=noninteractive
sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile


echo "Updating APT Packages..."
	sudo apt-get update > /dev/null
echo "Making sure our packages are updated..."
	sudo apt-get upgrade -y > /dev/null

echo "Provisioning Skeleton Server..."
echo "Add php repository..."
yes '' | sudo add-apt-repository ppa:ondrej/php

sudo apt-get update > /dev/null