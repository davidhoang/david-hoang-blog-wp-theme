#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
THEME_DIR="$ROOT/wp-content/themes/dh"
OUT_DIR="$ROOT/dist"
VERSION="${1:-}"

if [[ ! -d "$THEME_DIR" ]]; then
  echo "Theme directory not found: $THEME_DIR" >&2
  exit 1
fi

if [[ -z "$VERSION" ]]; then
  VERSION="$(grep -m1 '^Version:' "$THEME_DIR/style.css" | awk '{print $2}')"
fi

mkdir -p "$OUT_DIR"
ARCHIVE="$OUT_DIR/dh-${VERSION}.zip"

rm -f "$ARCHIVE"
(
  cd "$ROOT/wp-content/themes"
  zip -r "$ARCHIVE" dh \
    -x 'dh/node_modules/*' \
    -x 'dh/.DS_Store'
)

echo "Created $ARCHIVE"
