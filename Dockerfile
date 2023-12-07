FROM php:8.1-fpm-alpine

RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN docker-php-ext-install bcmath mysqli pdo pdo_mysql

COPY composer.json composer.json

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN /usr/local/bin/composer install --no-dev --no-scripts

COPY . .

RUN composer run-script post-autoload-dump

RUN chown -R www-data: .

CMD sh docker/startup.sh