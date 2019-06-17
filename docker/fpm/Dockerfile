FROM composer as composer
COPY ./ /var/www
WORKDIR /var/www
RUN composer install --ignore-platform-reqs

FROM kroshilin/php-docker-base:latest
COPY ./ /var/www
WORKDIR /var/www
RUN mkdir var && chmod 777 var && mkdir public/media && chmod 777 public/media
COPY --from=composer /var/www/vendor /var/www/vendor
