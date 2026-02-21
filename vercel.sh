#!/bin/bash

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
chmod -R 755 .

echo "Build completed!"