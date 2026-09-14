#!/usr/bin/env bash
#
# Idempotent setup for the dh WordPress theme dev environment.
# Installs system tooling (PHP, Composer, Docker, fuse-overlayfs), project
# dependencies (Composer + npm), and pre-pulls the WordPress/MySQL images so
# later boots are fast. Safe to run repeatedly.
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$REPO_ROOT"

export DEBIAN_FRONTEND=noninteractive

# --- System packages: PHP CLI (+ ext), Docker, fuse-overlayfs ---------------
needs_install=0
for pkg in php-cli php-mbstring php-xml php-curl unzip docker.io docker-compose-v2 fuse-overlayfs; do
  dpkg -s "$pkg" >/dev/null 2>&1 || needs_install=1
done
if [ "$needs_install" = "1" ]; then
  sudo apt-get update -y
  # Keep the maintainer defaults for any conffile prompts (e.g. /etc/fuse.conf).
  sudo apt-get install -y --no-install-recommends \
    -o Dpkg::Options::=--force-confdef -o Dpkg::Options::=--force-confold \
    php-cli php-mbstring php-xml php-curl unzip \
    docker.io docker-compose-v2 fuse-overlayfs
fi

# --- Composer (global) ------------------------------------------------------
if ! command -v composer >/dev/null 2>&1; then
  curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
  sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm -f /tmp/composer-setup.php
fi

# --- Docker config for this nested container --------------------------------
# overlay2 cannot apply whiteout files in a nested container ("operation not
# permitted"), so use fuse-overlayfs for image storage.
sudo mkdir -p /etc/docker
printf '{\n  "storage-driver": "fuse-overlayfs"\n}\n' | sudo tee /etc/docker/daemon.json >/dev/null
# The default iptables backend here is nft, but the kernel also enforces legacy
# tables whose FORWARD policy is DROP, which silently blocks container-to-
# container traffic. Pin Docker to the legacy backend so its rules take effect.
sudo update-alternatives --set iptables /usr/sbin/iptables-legacy >/dev/null 2>&1 || true
sudo update-alternatives --set ip6tables /usr/sbin/ip6tables-legacy >/dev/null 2>&1 || true
# Let the agent user run docker without sudo in new interactive shells.
sudo usermod -aG docker "$(id -un)" || true

# --- Project files & dependencies ------------------------------------------
[ -f .env ] || cp .env.example .env
# docker-compose.yml bind-mounts ./_development (read-only); keep it present.
mkdir -p _development

composer install --no-interaction --prefer-dist --no-progress
npm install --no-audit --no-fund

# --- Pre-pull Docker images so builds/boots don't wait on registry pulls ----
if ! sudo docker info >/dev/null 2>&1; then
  sudo bash -c 'nohup dockerd >/tmp/dockerd-install.log 2>&1 &'
  for _ in $(seq 1 30); do sudo docker info >/dev/null 2>&1 && break; sleep 1; done
fi
sudo docker compose pull || true

echo "install.sh complete."
