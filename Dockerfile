FROM php:8.1-cli

RUN apt-get update \
	&& apt-get install -y libicu-dev && docker-php-ext-install intl && docker-php-ext-enable intl \
	&& apt-get install -y libzip-dev && docker-php-ext-install zip && docker-php-ext-enable zip \
	&& pecl install xdebug && docker-php-ext-enable xdebug

# for PHPStan
RUN echo memory_limit=-1 > /usr/local/etc/php/conf.d/disable_memory_limit.ini

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
