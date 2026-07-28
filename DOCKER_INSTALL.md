# Docker Desktop install

Step-by-step guide for installing Docker Desktop on macOS so you can run the local WordPress environment for the **dh** theme.

## macOS

1. Download [Docker Desktop for Mac](https://www.docker.com/products/docker-desktop/) (Apple Silicon or Intel, depending on your Mac).
2. Open the `.dmg` and drag Docker to Applications.
3. Launch Docker Desktop from Applications and complete the setup wizard.
4. Wait until the menu bar whale icon shows **Docker Desktop is running**.

Verify from a terminal:

```bash
docker --version
docker compose version
```

Both commands should print version numbers.

## Linux (optional)

If you develop on Linux instead of macOS:

```bash
# Ubuntu / Debian example
sudo apt-get update
sudo apt-get install -y docker.io docker-compose-plugin
sudo usermod -aG docker "$USER"
```

Log out and back in, then verify with `docker compose version`.

## After Docker is installed

From the project root:

```bash
cp .env.example .env   # optional
npm run up
open http://localhost:8080
```

See [DEVELOPMENT.md](./DEVELOPMENT.md) for the full local workflow.

## Troubleshooting

**Docker Desktop won't start**

- Ensure virtualization is enabled (BIOS/firmware on Linux; Rosetta is not required for Apple Silicon builds).
- Restart Docker Desktop from the menu bar icon.

**Permission denied on Linux**

- Confirm your user is in the `docker` group: `groups | grep docker`
- Re-login after adding the group.

**Port conflicts**

- Set `WORDPRESS_PORT=8081` in `.env`, then run `npm run down && npm run up`.
