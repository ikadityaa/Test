#!/bin/bash

# Composer wrapper script to suppress deprecation warnings
export COMPOSER_ALLOW_SUPERUSER=1

# Suppress deprecation warnings by setting error reporting
php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_STRICT" /usr/bin/composer "$@"