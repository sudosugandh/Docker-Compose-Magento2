# Docker-Compose-Magento2 : v2.4.5

# Step 1:- Docker and Docker-compose Installation

Docker installation

sudo apt update

sudo apt install apt-transport-https ca-certificates curl software-properties-common

curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu focal stable"

sudo apt install docker-ce

docker-compose installation

sudo curl -L "https://github.com/docker/compose/releases/download/1.25.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

sudo chmod +x /usr/local/bin/docker-compose

sudo chown -R $USER:$USER /var/run/docker.sock

sudo systemctl restart docker.service

# Step 2:- Get docker environment project in your system:

HTTPS : https://github.com/sudosugandh/Docker-Compose-Magento2.git

SSH : git@github.com:sudosugandh/Docker-Compose-Magento2.git

# Step 3:- Build and Run Docker Project :-

Enter below command, it will build and run all docker containers :-

docker-compose up
 or 
docker-compose up --build

Change Database identity according to your need eg : database-name | username | password along with magento admin password & email

# Magento Installation Setup change according your data.

--base-url=http://localhost \
--db-host=localhost \
--db-name=magentodb \
--db-user=root \
--db-password=password \
--admin-firstname=admin \
--admin-lastname=admin \
--admin-email=sugandhxxx123xxx.com \
--admin-user=admin \
--admin-password=password \
--language=en_US \
--currency=USD \
--timezone=America/Chicago \
--use-rewrites=1

# Usefull commands...
find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +

find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +

chown -R :www-data .

chmod -R 777 var/ setup/ vendor/ pub/ bin/

chmod u+x bin/magento

php bin/magento c:c

php bin/magento c:f

/etc/init.d/apache2 restart

nano /etc/apache2/sites-available/000-default.conf 

/etc/init.d/apache2 restart

/etc/init.d/elasticsearch restart

![My Remote Image](https://user-images.githubusercontent.com/64632983/188258742-e8b9ea21-4cee-46b8-9ad3-1f993e08d2e7.png)
