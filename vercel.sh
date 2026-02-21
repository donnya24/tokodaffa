#!/bin/bash

# Install PHP extensions jika diperlukan
echo "Installing dependencies..."

# Install Composer jika belum ada
if ! command -v composer &> /dev/null; then
    echo "Composer not found, installing..."
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
    
    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
        echo 'ERROR: Invalid installer checksum'
        rm composer-setup.php
        exit 1
    fi
    
    php composer-setup.php --quiet
    rm composer-setup.php
    mv composer.phar /usr/local/bin/composer
fi

# Install dependencies
composer install --no-dev --prefer-dist --no-interaction

echo "Build completed successfully!"