# Running the Server

This guide explains how to start and run the WordPress development server for this project.

## Prerequisites

Before running the server, ensure you have:

1. **Docker Desktop installed and running**
   - See [DOCKER_INSTALL.md](./DOCKER_INSTALL.md) for installation instructions
   - Verify Docker is running (Docker icon should be visible in your menu bar)

2. **Node.js and npm installed** (optional, for live reload)
   - Check if installed: `node --version` and `npm --version`
   - If not installed, download from [nodejs.org](https://nodejs.org/)

## Quick Start

### 1. Start the WordPress Server

From the project root directory, run:

```bash
docker compose up -d
```

This command will:
- Download WordPress and MySQL Docker images (first time only)
- Start the database container
- Start the WordPress container
- Make WordPress available at **http://localhost:8080**

**First time setup:** The first time you run this, Docker will download the images, which may take a few minutes.

### 2. Access WordPress

Open your browser and navigate to:

- **WordPress Site**: http://localhost:8080
- **WordPress Admin**: http://localhost:8080/wp-admin

If this is your first time, you'll need to complete the WordPress installation wizard.

### 3. (Optional) Enable Live Reload for Development

For automatic browser refresh when editing theme files:

1. **Install dependencies** (first time only):
   ```bash
   npm install
   ```

2. **Start BrowserSync** in a separate terminal:
   ```bash
   npm run dev
   ```
   Or to automatically open your browser:
   ```bash
   npm run dev:open
   ```

3. **Access your site** through the BrowserSync URL (typically http://localhost:3000)
   - Changes to PHP, CSS, and JS files will automatically refresh your browser

## Common Commands

### Start the Server

```bash
docker compose up -d
```

The `-d` flag runs containers in detached mode (in the background).

### Stop the Server

```bash
docker compose down
```

This stops the containers but preserves all data.

### Stop and Remove All Data

```bash
docker compose down -v
```

**Warning:** This removes all volumes, including your database. Use this only if you want to start completely fresh.

### View Logs

View all container logs:
```bash
docker compose logs -f
```

View WordPress logs only:
```bash
docker compose logs -f wordpress
```

View database logs only:
```bash
docker compose logs -f db
```

Press `Ctrl+C` to exit log viewing.

### Check Container Status

```bash
docker compose ps
```

This shows which containers are running and their status.

### Restart the Server

```bash
docker compose restart
```

Or stop and start again:
```bash
docker compose down
docker compose up -d
```

## Development Workflow

### Standard Workflow

1. **Start Docker containers:**
   ```bash
   docker compose up -d
   ```

2. **Edit theme files** in `wp-content/themes/your-theme-name/`
   - Changes are immediately reflected (no restart needed)
   - Refresh your browser to see changes

3. **Stop when done:**
   ```bash
   docker compose down
   ```

### Live Reload Workflow

1. **Start Docker containers:**
   ```bash
   docker compose up -d
   ```

2. **Start BrowserSync** (in a separate terminal):
   ```bash
   npm run dev
   ```

3. **Access site** via BrowserSync URL (usually http://localhost:3000)

4. **Edit theme files** - browser automatically refreshes on save

5. **Stop BrowserSync:** Press `Ctrl+C` in the BrowserSync terminal

6. **Stop Docker containers:**
   ```bash
   docker compose down
   ```

## Configuration

### Changing the Port

If port 8080 is already in use, create a `.env` file in the project root:

```env
WORDPRESS_PORT=8081
```

Then restart:
```bash
docker compose down
docker compose up -d
```

### Database Credentials

Default database credentials (set in `docker-compose.yml`):
- **Database Name**: `wordpress`
- **Database User**: `wordpress`
- **Database Password**: `wordpress`
- **Root Password**: `rootpassword`

To change these, create a `.env` file:

```env
MYSQL_DATABASE=your_database
MYSQL_USER=your_user
MYSQL_PASSWORD=your_password
MYSQL_ROOT_PASSWORD=your_root_password
```

Then restart the containers.

## Troubleshooting

### Port Already in Use

If you see an error about port 8080 being in use:

1. Check what's using the port:
   ```bash
   lsof -i :8080
   ```

2. Either stop the conflicting service or change the port (see Configuration section above)

### Containers Won't Start

1. **Check Docker is running:**
   - Ensure Docker Desktop is running (check menu bar icon)
   - Try restarting Docker Desktop

2. **Check logs:**
   ```bash
   docker compose logs
   ```

3. **Check container status:**
   ```bash
   docker compose ps
   ```

### Database Connection Errors

1. **Ensure database container is running:**
   ```bash
   docker compose ps
   ```

2. **Check database logs:**
   ```bash
   docker compose logs db
   ```

3. **Restart containers:**
   ```bash
   docker compose restart
   ```

### Permission Issues

If you encounter file permission errors:

```bash
docker compose exec wordpress chown -R www-data:www-data /var/www/html/wp-content
```

### Reset Everything

To completely reset and start fresh:

```bash
docker compose down -v
docker compose up -d
```

**Warning:** This deletes all data including your WordPress database and content.

### WordPress Installation Issues

If WordPress doesn't load or shows errors:

1. **Check WordPress logs:**
   ```bash
   docker compose logs wordpress
   ```

2. **Verify containers are running:**
   ```bash
   docker compose ps
   ```

3. **Try accessing directly:**
   - http://localhost:8080
   - If you see a WordPress installation page, complete the setup

## Accessing the Database

### Via Command Line

```bash
docker compose exec db mysql -u wordpress -pwordpress wordpress
```

### Via WordPress Container (WP-CLI)

If WP-CLI is available:

```bash
docker compose exec wordpress wp db query "SHOW TABLES;"
```

## File Locations

- **WordPress Site**: http://localhost:8080
- **Theme Files**: `./wp-content/themes/`
- **Plugin Files**: `./wp-content/plugins/`
- **Uploads**: `./wp-content/uploads/`
- **Docker Compose Config**: `./docker-compose.yml`
- **PHP Config**: `./php.ini`

## Notes

- The server runs in the background when using `-d` flag
- Theme files are mounted from your local `wp-content` directory, so changes persist
- Database data is stored in Docker volumes and persists between restarts
- Use `docker compose down -v` to completely remove all data
- This setup is for **local development only** - do not use in production

## Getting Help

If you encounter issues:

1. Check the logs: `docker compose logs -f`
2. Verify Docker is running
3. Check the [README.md](./README.md) for more detailed information
4. Review [DOCKER_INSTALL.md](./DOCKER_INSTALL.md) if Docker isn't working
