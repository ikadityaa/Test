# Composer Deprecation Warnings Fix

## Problem
PHP 8.4 with older Composer versions shows deprecation warnings like:
```
PHP Deprecated: Return type of Symfony\Component\Console\Helper\HelperSet::getIterator() should either be compatible with IteratorAggregate::getIterator(): Traversable, or the #[\ReturnTypeWillChange] attribute should be used to temporarily suppress the notice
```

## Solution
We've implemented multiple ways to suppress these deprecation warnings:

### Option 1: Use the Wrapper Script (Recommended)
```bash
./composer-wrapper.sh install
./composer-wrapper.sh update
./composer-wrapper.sh require package-name
```

### Option 2: Use the Alias
```bash
# Source the alias file
source .bashrc-composer

# Then use composer normally
composer install
composer update
```

### Option 3: Direct PHP Command
```bash
php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_STRICT" /usr/bin/composer install
```

### Option 4: Global PHP Configuration
A PHP configuration file has been created at `/etc/php/8.4/cli/conf.d/99-deprecation-suppress.ini` that suppresses deprecation warnings globally.

## Current Setup
- **PHP Version**: 8.4.5
- **Composer Version**: 2.8.10
- **Laravel Version**: 12.22.1
- **PHP Requirement**: ^8.2 (compatible with 8.2, 8.3, 8.4)

## Verification
To verify the fix is working:
```bash
./composer-wrapper.sh install
```
Should run without any deprecation warnings.

## Notes
- The deprecation warnings are cosmetic and don't affect functionality
- This is a temporary fix until Composer updates their codebase
- The project works perfectly with PHP 8.2+ as specified in composer.json