#!/usr/bin/env bash
#
# Per-boot startup for the dh WordPress theme dev environment.
# Starts the Docker daemon, brings up WordPress + MySQL, and (on first run)
# completes the WordPress install, activates the dh theme, and seeds a little
# sample content. Idempotent: safe to run on every boot.
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$REPO_ROOT"

SITE_URL="http://localhost:8080"

# --- Docker networking/storage config (idempotent, belt-and-suspenders) -----
# These are normally set by install.sh, but re-apply before the daemon starts
# so a booted snapshot always has the nested-container fixes in place.
sudo mkdir -p /etc/docker
printf '{\n  "storage-driver": "fuse-overlayfs"\n}\n' | sudo tee /etc/docker/daemon.json >/dev/null
sudo update-alternatives --set iptables /usr/sbin/iptables-legacy >/dev/null 2>&1 || true
sudo update-alternatives --set ip6tables /usr/sbin/ip6tables-legacy >/dev/null 2>&1 || true

# --- Docker daemon ----------------------------------------------------------
if ! sudo docker info >/dev/null 2>&1; then
  sudo bash -c 'nohup dockerd >/tmp/dockerd.log 2>&1 &'
  for _ in $(seq 1 60); do sudo docker info >/dev/null 2>&1 && break; sleep 1; done
fi
sudo docker info >/dev/null 2>&1 || { echo "Docker daemon failed to start"; tail -n 40 /tmp/dockerd.log || true; exit 1; }

# --- WordPress + MySQL ------------------------------------------------------
sudo docker compose up -d

echo "Waiting for WordPress to respond..."
ready=0
for _ in $(seq 1 60); do
  code="$(curl -s -o /dev/null -w '%{http_code}' "$SITE_URL/wp-admin/install.php" || true)"
  if [ "$code" = "200" ]; then ready=1; break; fi
  sleep 3
done
[ "$ready" = "1" ] || { echo "WordPress did not become ready"; sudo docker compose logs --tail=40; exit 1; }

# --- WP-CLI (fetched into the ephemeral container layer) --------------------
sudo docker compose exec -T wordpress bash -c '
  command -v wp >/dev/null 2>&1 || {
    curl -sS -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x /usr/local/bin/wp
  }'

# --- First-run install + theme activation + sample content ------------------
# NOTE: admin/admin123 are throwaway credentials for this local-only instance,
# matching the local dev defaults already in docker-compose.yml / .env.example.
sudo docker compose exec -T wordpress bash -c '
  set -e
  if ! wp core is-installed --allow-root 2>/dev/null; then
    wp core install --url="http://localhost:8080" --title="David Hoang — Dev" \
      --admin_user="admin" --admin_password="admin123" \
      --admin_email="admin@example.com" --skip-email --allow-root
    wp post generate --count=5 --post_type=post --allow-root
    wp post create --post_type=page --post_title="About" --post_status=publish --allow-root >/dev/null
  fi
  wp theme activate dh --allow-root
'

echo "WordPress is ready at $SITE_URL  (admin / admin123)"
