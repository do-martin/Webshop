# Use the official PHP image with Apache
FROM php:8.1-apache

# Install SQL Server drivers and other dependencies
RUN apt-get update && \
    apt-get install -y \
    libodbc1 \
    unixodbc \
    unixodbc-dev \
    gnupg \
    curl \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 \
    && pecl install pdo_sqlsrv \
    && docker-php-ext-enable pdo_sqlsrv \
    && rm -rf /var/lib/apt/lists/*

# Installiere Composer, falls notwendig
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Copy the source code into the Docker image
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Installiere die PHP-Abhängigkeiten (falls vorhanden)
RUN composer install

# Kopiere die .env-Datei, falls erforderlich
# COPY .env /var/www/html/.env  
# Nur wenn du die .env-Datei ins Image integrieren möchtest

# Expose port 80 for the web server
EXPOSE 80

# Starte den Apache-Webserver
CMD ["apache2-foreground"]