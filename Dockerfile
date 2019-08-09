FROM php:7.2-apache
MAINTAINER SMK <smk.yodjunda@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && rm -rf /var/lib/apt/lists/* \
    && echo "always_populate_raw_post_data=-1" > /usr/local/etc/php/php.ini \
	&& echo "date.timezone = \"ASIA/Bangkok\""> /usr/local/etc/php/php.ini

RUN curl -sSL https://getcomposer.org/composer.phar -o /usr/bin/composer \
    && chmod +x /usr/bin/composer \
    && apt-get update && apt-get install -y zlib1g-dev git && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip \
    && apt-get purge -y --auto-remove zlib1g-dev \
    && composer selfupdate

ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

	
ADD . /var/www/
RUN cd /var/www/ && composer update

WORKDIR /var/www/public

RUN a2enmod rewrite
RUN usermod -u 1000 www-data && chown -R www-data:www-data /var/www && chmod 755 -R /var/www
