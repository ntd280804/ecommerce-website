FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

# Set Apache document root to Public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/Public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/Public>|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Add alias for Admin directory
RUN echo "Alias /Admin /var/www/html/Admin" >> /etc/apache2/sites-available/000-default.conf
RUN echo "<Directory /var/www/html/Admin>" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf
RUN echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
