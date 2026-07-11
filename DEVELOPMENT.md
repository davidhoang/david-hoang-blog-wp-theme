# Local development — dh WordPress theme

This repo runs a local WordPress instance in Docker so you can develop and test the **dh** theme at `wp-content/themes/dh/`.

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) for Mac
- Node.js 18+ (optional, for live reload)

Verify Docker is available:

```bash
docker --version
docker compose version
```

See [DOCKER_INSTALL.md](./DOCKER_INSTALL.md) for a step-by-step Docker Desktop install guide.

## Quick start

From the project root:

```bash
# 1. Optional: copy environment defaults
cp .env.example .env

# 2. Start WordPress + MySQL
npm run up
# or: docker compose up -d

# 3. Open the site
open http://localhost:8080
```

On first visit, complete the WordPress install wizard:

1. Choose a site title (e.g. "David Hoang — Dev")
2. Create an admin user and password
3. Log in at http://localhost:8080/wp-admin

Then activate the theme:

1. Go to **Appearance → Themes**
2. Activate **dh**

## Daily workflow

| Task | Command |
|------|---------|
| Start environment | `npm run up` |
| Stop environment | `npm run down` |
| View logs | `npm run logs` |
| Reset database + WordPress data | `npm run reset` |
| Live reload while editing theme | `npm run dev` |

Theme files live in `wp-content/themes/dh/`. Changes to PHP, CSS, and JS are picked up immediately — no container restart needed.

### Live reload (optional)

In a second terminal:

```bash
npm install   # first time only
npm run dev
```

BrowserSync proxies http://localhost:8080 (usually at http://localhost:3000) and refreshes when theme files change.

## Project layout

```
.
├── docker-compose.yml      # WordPress + MySQL services
├── .env.example            # Copy to .env to customize ports/credentials
├── php.ini                 # PHP upload/memory limits for the container
├── DEVELOPMENT.md          # This file
└── wp-content/
    └── themes/
        └── dh/             # Your custom theme
            ├── style.css   # Theme metadata + base styles
            ├── functions.php
            ├── header.php
            ├── footer.php
            └── index.php
```

WordPress core and the database persist in Docker volumes. Only `wp-content/` is mounted from this repo, so theme edits are always local.

## Configuration

Copy `.env.example` to `.env` to override defaults:

| Variable | Default | Purpose |
|----------|---------|---------|
| `WORDPRESS_PORT` | `8080` | Local site URL port |
| `WORDPRESS_DEBUG` | `1` | Enable WordPress debug mode |
| `MYSQL_DATABASE` | `wordpress` | Database name |
| `MYSQL_USER` | `wordpress` | Database user |
| `MYSQL_PASSWORD` | `wordpress` | Database password |

After changing `.env`, restart:

```bash
npm run down && npm run up
```

## Useful commands

```bash
# Shell into the WordPress container
docker compose exec wordpress bash

# MySQL shell
docker compose exec db mysql -u wordpress -pwordpress wordpress

# Stop and remove all data (fresh install)
docker compose down -v
```

## Importing content (optional)

To mirror posts from a live site, see [CONTENT_IMPORT.md](./CONTENT_IMPORT.md).

## Troubleshooting

**Port 8080 already in use**

Set `WORDPRESS_PORT=8081` in `.env`, then `npm run down && npm run up`.

**Theme not showing in admin**

Confirm files exist at `wp-content/themes/dh/style.css` with a valid `Theme Name:` header.

**Database connection errors**

```bash
docker compose ps
docker compose logs db
```

**Start completely fresh**

```bash
npm run reset
npm run up
```

Then revisit http://localhost:8080 and run the install wizard again.

## Next steps

With the environment running and **dh** activated, you can start building out templates (`single.php`, `page.php`, `archive.php`, etc.), styles, and theme features.
