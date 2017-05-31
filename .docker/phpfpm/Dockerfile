FROM php:7-fpm

RUN apt-get update -y && apt-get install -y \
    tesseract-ocr \
    libmcrypt-dev \
    git \
    zlib1g-dev \
    libfreetype6-dev \
    libpng12-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install zip \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir /app

WORKDIR /app
CMD ["php-fpm", "--allow-to-run-as-root", "--fpm-config", "/app/.docker/phpfpm/fpm.conf"]
