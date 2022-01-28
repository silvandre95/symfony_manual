# FROM toasterlint/php-apache-mysql:8.0-nowarn
FROM webdevops/php-apache:8.0-alpine

WORKDIR /app

ENV PHP_DATE_TIMEZONE=Europe/Paris
ENV WEB_DOCUMENT_ROOT=/app/public/

COPY install.sh /opt/docker/provision/entrypoint.d/30-install.sh