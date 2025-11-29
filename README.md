# Local WordPress Development Environment

This repository contains a Docker-based local WordPress development environment for testing the WordPress theme from [blog.davidhoang.com](https://blog.davidhoang.com/).

## Prerequisites

### Docker Desktop Installation

1. **Download Docker Desktop for Mac**
   - Visit: https://www.docker.com/products/docker-desktop/
   - Download the version for Apple Silicon (M1/M2/M3) or Intel, depending on your Mac
   - Open the downloaded `.dmg` file and drag Docker to Applications

2. **Install and Start Docker Desktop**
   - Launch Docker Desktop from Applications
   - Complete the initial setup wizard
   - Wait for Docker to start (you'll see the Docker icon in your menu bar)

3. **Verify Installation**
   ```bash
   docker --version
   docker compose version
   ```
   Both commands should return version numbers.

## Quick Start

1. **Clone or navigate to this repository**
   ```bash
   cd blog-dot-davidhoang-dot-com
   ```

2. **Start the WordPress environment**
   ```bash
   docker compose up -d
   ```
   This will:
   - Download WordPress and MySQL images (first time only)
   - Start the database and WordPress containers
   - Make WordPress available at http://localhost:8080

3. **Access WordPress**
   - Open http://localhost:8080 in your browser
   - Complete the WordPress installation wizard
   - Note your admin credentials for future use

## Common Commands

### Start the environment
```bash
docker compose up -d
```

### Stop the environment
```bash
docker compose down
```

### Stop and remove volumes (clean slate)
```bash
docker compose down -v
```

### View logs
```bash
docker compose logs -f
```

### View WordPress logs only
```bash
docker compose logs -f wordpress
```

### Access WordPress container shell
```bash
docker compose exec wordpress bash
```

### Access MySQL database
```bash
docker compose exec db mysql -u wordpress -pwordpress wordpress
```

## Theme Setup

### Extracting Theme from Live Site

You have several options to get your theme from the live site:

#### Option 1: Using SFTP/SSH (Recommended)

1. **Connect to your live server** via SFTP or SSH
2. **Navigate to the WordPress themes directory**: `/wp-content/themes/`
3. **Download your theme folder** to `./wp-content/themes/` in this project
4. **Example using SCP:**
   ```bash
   scp -r user@your-server.com:/path/to/wp-content/themes/your-theme-name ./wp-content/themes/
   ```

#### Option 2: Using WordPress Admin

1. **Install a plugin** like "Export Theme" or use FTP credentials
2. **Download the theme** as a ZIP file
3. **Extract it** to `./wp-content/themes/` in this project

#### Option 3: Using WP-CLI (if available on live site)

```bash
# On your live server
wp theme export your-theme-name --path=/path/to/wordpress

# Then download and extract to ./wp-content/themes/
```

### Activating the Theme

1. **Ensure the theme is in the correct location:**
   ```
   ./wp-content/themes/your-theme-name/
   ```

2. **Start Docker containers** (if not already running):
   ```bash
   docker compose up -d
   ```

3. **Access WordPress admin**: http://localhost:8080/wp-admin

4. **Navigate to**: Appearance → Themes

5. **Activate your theme**

## Content Import

### Method 1: WordPress Export/Import (Recommended)

1. **Export from Live Site:**
   - Log into your live WordPress admin
   - Go to: Tools → Export
   - Choose "All content" or select specific content types
   - Click "Download Export File"
   - Save the `.xml` file

2. **Import to Local Site:**
   - Log into local WordPress admin: http://localhost:8080/wp-admin
   - Go to: Tools → Import
   - Install "WordPress Importer" if prompted
   - Upload the `.xml` file you exported
   - Follow the import wizard
   - Assign authors if needed

### Method 2: Database Import (Advanced)

1. **Export database from live site:**
   ```bash
   # On live server or via hosting panel
   mysqldump -u username -p database_name > export.sql
   ```

2. **Import to local database:**
   ```bash
   # Copy the SQL file to this directory
   docker compose exec -T db mysql -u wordpress -pwordpress wordpress < export.sql
   ```

3. **Update URLs in database:**
   ```bash
   # Access WordPress container
   docker compose exec wordpress bash
   
   # Install WP-CLI if not available, or use search-replace plugin
   # Or manually update wp_options table
   ```

4. **Update site URL:**
   - Access phpMyAdmin or use WP-CLI:
   ```bash
   docker compose exec wordpress wp option update home 'http://localhost:8080'
   docker compose exec wordpress wp option update siteurl 'http://localhost:8080'
   ```

### Method 3: Using WP-CLI (if available)

```bash
# Export from live site
wp db export export.sql

# Import to local
docker compose exec wordpress wp db import export.sql

# Search and replace URLs
docker compose exec wordpress wp search-replace 'https://blog.davidhoang.com' 'http://localhost:8080'
```

## Development Workflow

### Live Reload Setup

For automatic browser refresh when you make changes:

1. **Install Node.js dependencies** (first time only):
   ```bash
   npm install
   ```

2. **Start BrowserSync** (in a separate terminal):
   ```bash
   npm run dev
   ```
   This will:
   - Start a proxy server (usually at http://localhost:3000)
   - Watch for changes in PHP, CSS, and JS files
   - Automatically refresh your browser when files change

3. **Access your site** through the BrowserSync URL (shown in terminal, typically http://localhost:3000)

4. **Edit your theme files** - changes will automatically refresh in the browser!

**Note:** Keep BrowserSync running in a separate terminal while developing. Press `Ctrl+C` to stop it.

### Making Theme Changes

1. **Edit theme files** in `./wp-content/themes/your-theme-name/`
2. **Changes are immediately reflected** (no restart needed)
3. **Browser automatically refreshes** if using BrowserSync, or manually refresh your browser

### Debugging

WordPress debug mode is enabled by default. Check logs:
```bash
docker compose logs -f wordpress
```

Debug output will appear in:
- Browser (if `WP_DEBUG_DISPLAY` is enabled)
- `./wp-content/debug.log` (if `WP_DEBUG_LOG` is enabled)

### Installing Plugins

1. **Via WordPress Admin:**
   - Go to Plugins → Add New
   - Install and activate as usual

2. **Manually:**
   - Download plugin ZIP
   - Extract to `./wp-content/plugins/`
   - Activate in WordPress admin

## Configuration

### Changing Port

Edit `.env` file:
```env
WORDPRESS_PORT=8080
```

Then restart:
```bash
docker compose down
docker compose up -d
```

### Database Credentials

Edit `.env` file to change database credentials. Default values:
- Database: `wordpress`
- User: `wordpress`
- Password: `wordpress`
- Root Password: `rootpassword`

**Important:** Change these in production!

## Troubleshooting

### Port Already in Use

If port 8080 is already in use:
1. Edit `.env` and change `WORDPRESS_PORT` to another port (e.g., 8081)
2. Restart containers: `docker compose down && docker compose up -d`

### Permission Issues

If you encounter permission issues with theme files:
```bash
docker compose exec wordpress chown -R www-data:www-data /var/www/html/wp-content
```

### Database Connection Errors

1. Ensure database container is running: `docker compose ps`
2. Check database logs: `docker compose logs db`
3. Verify credentials in `.env` match `docker-compose.yml`

### Reset Everything

To start completely fresh:
```bash
docker compose down -v
docker compose up -d
```
This removes all data including the database.

## File Structure

```
blog-dot-davidhoang-dot-com/
├── docker-compose.yml      # Docker services configuration
├── package.json           # Node.js dependencies for live reload
├── .env                    # Environment variables
├── .gitignore             # Git ignore rules
├── README.md              # This file
└── wp-content/            # WordPress content (themes, plugins, uploads)
    ├── themes/
    │   └── your-theme-name/  # Your theme goes here
    ├── plugins/
    └── uploads/
```

## Additional Resources

- [WordPress Codex](https://codex.wordpress.org/)
- [Docker Documentation](https://docs.docker.com/)
- [WordPress Developer Handbook](https://developer.wordpress.org/)

## Notes

- The WordPress installation persists in Docker volumes
- Theme files are mounted from `./wp-content`, so changes persist
- Database data persists in a Docker volume (use `docker compose down -v` to remove)
- This setup is for **local development only** - do not use in production
