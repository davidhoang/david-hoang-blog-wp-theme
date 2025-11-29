# Theme Extraction Guide

This guide explains how to extract your WordPress theme from the live site (blog.davidhoang.com) and set it up in your local development environment.

## Prerequisites

- Access to your live WordPress site (SFTP, SSH, or hosting control panel)
- Local Docker environment running (see README.md)

## Method 1: Using SFTP (Recommended)

### Step 1: Connect to Your Server

1. **Get your server credentials:**
   - SFTP hostname
   - Username
   - Password (or SSH key)

2. **Connect using an SFTP client:**
   - **macOS built-in:** Use Terminal with `sftp` command
   - **GUI clients:** FileZilla, Cyberduck, Transmit, or VS Code SFTP extension

### Step 2: Locate Your Theme

1. Navigate to your WordPress installation directory
2. Go to: `/wp-content/themes/`
3. Identify your active theme folder (check WordPress admin → Appearance → Themes if unsure)

### Step 3: Download the Theme

#### Using Terminal (SFTP):

```bash
# Connect to your server
sftp username@your-server.com

# Navigate to themes directory
cd /path/to/wordpress/wp-content/themes

# Download the theme folder
get -r your-theme-name

# Exit SFTP
exit
```

#### Using SCP (Alternative):

```bash
scp -r username@your-server.com:/path/to/wordpress/wp-content/themes/your-theme-name ./wp-content/themes/
```

#### Using GUI Client:

1. Connect to your server
2. Navigate to `/wp-content/themes/`
3. Download the entire theme folder
4. Place it in `./wp-content/themes/` in your local project

### Step 4: Verify Theme Location

Your theme should be located at:
```
./wp-content/themes/your-theme-name/
```

The folder should contain files like:
- `style.css`
- `index.php`
- `functions.php`
- Other theme files

## Method 2: Using SSH (If Available)

If you have SSH access to your server:

```bash
# SSH into your server
ssh username@your-server.com

# Navigate to themes directory
cd /path/to/wordpress/wp-content/themes

# Create a tar archive
tar -czf your-theme-name.tar.gz your-theme-name/

# Exit SSH
exit

# Download the archive
scp username@your-server.com:/path/to/wordpress/wp-content/themes/your-theme-name.tar.gz ./

# Extract locally
tar -xzf your-theme-name.tar.gz -C ./wp-content/themes/

# Clean up
rm your-theme-name.tar.gz
```

## Method 3: Using Hosting Control Panel

Many hosting providers offer file managers:

1. **Log into your hosting control panel** (cPanel, Plesk, etc.)
2. **Open File Manager**
3. **Navigate to:** `public_html/wp-content/themes/` (or your WordPress root)
4. **Compress the theme folder:**
   - Right-click → Compress
   - Select ZIP format
5. **Download the ZIP file**
6. **Extract locally:**
   ```bash
   unzip your-theme-name.zip -d ./wp-content/themes/
   ```

## Method 4: Using WordPress Admin (Limited)

Some themes can be exported if they're available in the WordPress theme directory:

1. **Log into live WordPress admin**
2. **Go to:** Appearance → Themes
3. **If your theme is a child theme or custom theme**, this method won't work
4. **For themes from WordPress.org**, you can download them directly

**Note:** This method typically only works for themes from the WordPress theme repository.

## Method 5: Using WP-CLI (If Installed on Live Site)

If WP-CLI is available on your live server:

```bash
# SSH into your server
ssh username@your-server.com

# Navigate to WordPress root
cd /path/to/wordpress

# List themes to find your theme name
wp theme list

# Export theme (if supported)
# Note: WP-CLI doesn't have a direct theme export, but you can:
# 1. Use the theme path to create an archive
# 2. Or use standard file transfer methods above
```

## Verifying Theme Extraction

1. **Check theme structure:**
   ```bash
   ls -la ./wp-content/themes/your-theme-name/
   ```

2. **Verify essential files exist:**
   - `style.css` (required - contains theme header)
   - `index.php` (required - main template)
   - `functions.php` (usually present)

3. **Check theme header in style.css:**
   ```bash
   head -20 ./wp-content/themes/your-theme-name/style.css
   ```
   
   Should contain something like:
   ```css
   /*
   Theme Name: Your Theme Name
   Theme URI: https://example.com
   Author: Your Name
   ...
   */
   ```

## Activating the Theme Locally

1. **Ensure Docker is running:**
   ```bash
   docker compose up -d
   ```

2. **Access WordPress admin:**
   - Open http://localhost:8080/wp-admin
   - Log in with your admin credentials

3. **Activate the theme:**
   - Go to: Appearance → Themes
   - Find your theme in the list
   - Click "Activate"

## Troubleshooting

### Theme Not Appearing in WordPress Admin

1. **Check file permissions:**
   ```bash
   ls -la ./wp-content/themes/
   ```
   Ensure the theme folder is readable

2. **Verify style.css exists and has correct header:**
   ```bash
   cat ./wp-content/themes/your-theme-name/style.css | head -20
   ```

3. **Check WordPress debug mode:**
   - Look for errors in browser console
   - Check Docker logs: `docker compose logs wordpress`

### Missing Files After Download

1. **Ensure you downloaded the entire theme folder**, not just individual files
2. **Check for hidden files** (files starting with `.`)
3. **Verify the theme structure matches** what's on the live site

### Permission Issues

If WordPress can't read the theme:

```bash
# Fix permissions (run from project root)
docker compose exec wordpress chown -R www-data:www-data /var/www/html/wp-content/themes/your-theme-name
docker compose exec wordpress chmod -R 755 /var/www/html/wp-content/themes/your-theme-name
```

## Next Steps

After extracting and activating your theme:

1. **Import content** from your live site (see CONTENT_IMPORT.md)
2. **Start developing** - edit files in `./wp-content/themes/your-theme-name/`
3. **Changes are live** - refresh your browser to see updates

## Security Note

**Important:** Before committing your theme to version control:

1. **Review the theme files** for sensitive information:
   - API keys
   - Database credentials
   - Hardcoded URLs
   - Personal information

2. **Add sensitive files to .gitignore** if needed

3. **Consider using environment variables** for configuration
