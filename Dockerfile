FROM php:7.2-apache
MAINTAINER SMK <smk.yodjunda@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

RUN requirements="libpng-dev libjpeg-dev libjpeg62-turbo libmcrypt4 libmcrypt-dev libcurl3-dev libxml2-dev libxslt-dev libicu-dev " \
    && apt-get update && apt-get install -y $requirements && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/lib \
    && docker-php-ext-install gd \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install soap \
    && docker-php-ext-install xsl \
    && docker-php-ext-install intl \
	&& apt-get update && apt-get install -y git \
    && requirementsToRemove="libpng-dev libjpeg-dev libmcrypt-dev libcurl3-dev libxml2-dev libicu-dev" \
    && apt-get purge --auto-remove -y $requirementsToRemove \
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
