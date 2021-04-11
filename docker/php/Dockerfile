FROM php:7.4-apache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

COPY apache2.conf /etc/apache2/sites-available/000-default.conf