#!/usr/bin/env bash
set -euo pipefail

# Usage: ./build-release.sh v1.0.0
VERSION=${1:-snapshot}
ARTIFACT="qr-attendance-${VERSION}.zip"

if ! command -v composer >/dev/null 2>&1; then
  echo "composer not found. Please run this on a machine with composer installed." >&2
  exit 1
fi

if ! command -v npm >/dev/null 2>&1; then
  echo "npm not found. Please run this on a machine with node/npm installed." >&2
  exit 1
fi

# Install PHP deps (no dev)
composer install --no-dev --prefer-dist --optimize-autoloader

# Build frontend assets
npm ci
npm run build

# Optimize app
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Exclude files for production
EXCLUDES=(
  ".git" ".github" "tests" "node_modules" "storage/logs/*" "storage/framework/cache/*" "storage/framework/sessions/*" "storage/framework/views/*" ".env"
)

zip -r "$ARTIFACT" . -x ${EXCLUDES[@]}

echo "Created $ARTIFACT"