#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "==> PHP syntax check"
find wp-content/themes/dh -name '*.php' -print0 | xargs -0 -n1 php -l

echo "==> PHPUnit"
if [[ ! -x vendor/bin/phpunit ]]; then
  composer install --no-interaction --prefer-dist --no-progress
fi
vendor/bin/phpunit

echo "==> ESLint"
npm run lint

echo "==> Prettier"
npm run format:check

echo "Smoke checks passed."
