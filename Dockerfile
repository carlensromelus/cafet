FROM php:8.2-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install mysqli mbstring \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --chown=www-data:www-data . /var/www/html

RUN find /var/www/html -type d -exec chmod 755 {} + \
    && find /var/www/html -type f -exec chmod 644 {} + \
    && chown -R www-data:www-data /var/www/html

EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t /var/www/html"]
