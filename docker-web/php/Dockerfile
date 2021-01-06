FROM php:7.4-fpm-alpine

RUN docker-php-ext-install -j$(nproc) mysqli opcache

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get -y install openssl

ADD bash_history /root/.bash_history
ADD dades /tmp/dades

RUN echo "alias ll='ls -l'" >> /root/.bashrc
RUN bash -c "history -a"

WORKDIR /root