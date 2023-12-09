FROM php:8.2-apache
WORKDIR /var/www/html
COPY . /var/www/html/
RUN \ 
apt-get update && \
apt-get install libldap2-dev -y && \
rm -rf /var/lib/apt/lists/* && \
docker-php-ext-configure ldap && \
docker-php-ext-install ldap

