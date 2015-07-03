#!/usr/bin/env bash

apt-get update -y

apt-get install -y python-software-properties
add-apt-repository ppa:ondrej/php5-oldstable

apt-get update -y

echo mysql-server mysql-server/root_password password root | sudo debconf-set-selections
echo mysql-server mysql-server/root_password_again password root | sudo debconf-set-selections
apt-get install -y mysql-server

apt-get install -y curl

apt-get install -y apache2
rm -rf /var/www
ln -fs /srv /var/www

apt-get install -y php5 libapache2-mod-php5 php5-cli php5-common php5-curl php5-mcrypt php5-mysql php5-imagick php5-gd

apt-get install -y git
apt-get install -y vim

a2enmod rewrite

### Uncomment these 2 lines if you have write permission issues in the yii application
mv /etc/apache2/envvars /etc/apache2/envvars.backup
cp /srv/vm/envvars /etc/apache2
rm -rf /var/lock/apache2

mv /etc/apache2/sites-available/default /etc/apache2/sites-available/default.backup
cp /srv/vm/default /etc/apache2/sites-available

service apache2 restart
