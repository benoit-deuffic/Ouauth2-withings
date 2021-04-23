FROM composer:latest as composer

FROM bdeuffic/alpine-php-dev-base

# Install packages
RUN apk update \
    && \
    # apt Debian packages
    apk add \
        nano \
        postgresql-dev \
    && \
    # Install Composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

COPY --from=composer  /usr/bin/composer /usr/local/bin/composer

#RUN composer global require hirak/prestissimo --no-plugins --no-scripts

ENV PATH=/var/www/project/app/vendor/bin:$PATH
ENV COMPOSER_MEMORY_LIMIT=-1

WORKDIR /var/www/project

# Create a symfony 4.4 complete project
RUN if [ ! -d ./app -o ! "$(ls -A ./app)" ]; then \
      composer create-project symfony/website-skeleton app ^4.4; \
    fi

# Create the cache directory and make it writeable by PHP
CMD mkdir -p /var/www/project/app/var/cache \ 
    && \
    chown -R www-data:www-data /var/www/project/app/var

WORKDIR /var/www/project/app

CMD ln -s /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

CMD php-fpm

