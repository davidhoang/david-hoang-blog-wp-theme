# Content Import Guide

This guide explains how to import content (posts, pages, media, etc.) from your live WordPress site (blog.davidhoang.com) into your local development environment.

## Prerequisites

- Local WordPress environment running (see README.md)
- Access to your live WordPress admin panel
- Theme already extracted and activated (see THEME_EXTRACTION.md)

## Method 1: WordPress Export/Import (Recommended for Most Users)

This is the easiest and safest method for importing content.

### Step 1: Export from Live Site

1. **Log into your live WordPress admin:**
   - Go to https://blog.davidhoang.com/wp-admin
   - Log in with your credentials

2. **Navigate to Export:**
   - Go to: **Tools → Export**

3. **Choose what to export:**
   - **All content** (recommended for first import)
   - Or select specific content types (posts, pages, media, etc.)

4. **Download the export file:**
   - Click "Download Export File"
   - Save the `.xml` file to your computer
   - Note the file location

### Step 2: Import to Local Site

1. **Ensure local WordPress is running:**
   ```bash
   docker compose up -d
   ```

2. **Access local WordPress admin:**
   - Open http://localhost:8080/wp-admin
   - Log in (or complete installation if first time)

3. **Install WordPress Importer:**
   - Go to: **Tools → Import**
   - Click "WordPress" under "Import"
   - Click "Install Now" if the importer isn't installed
   - Click "Run Importer" after installation

4. **Upload the export file:**
   - Click "Choose File"
   - Select the `.xml` file you downloaded
   - Click "Upload file and import"

5. **Configure import settings:**
   - **Assign authors:** Choose to import authors or map to existing users
   - **Import attachments:** Check this to download media files
   - Click "Submit"

6. **Wait for import to complete:**
   - The process may take a few minutes depending on content size
   - You'll see a success message when done

### Step 3: Update URLs

After import, you need to update URLs from the live site to local:

1. **Install Search & Replace plugin** (temporary):
   - Go to: **Plugins → Add New**
   - Search for "Better Search Replace" or "Search Replace DB"
   - Install and activate

2. **Run search and replace:**
   - Go to the plugin's settings page
   - **Search for:** `https://blog.davidhoang.com`
   - **Replace with:** `http://localhost:8080`
   - Select all tables
   - Click "Run Search/Replace"
   - **Important:** Test on a single table first, then do all tables

3. **Deactivate and delete the plugin** after use (optional, for security)

## Method 2: Database Import (Advanced)

This method imports the entire database, including settings, users, and all content.

### Step 1: Export Database from Live Site

#### Option A: Using Hosting Control Panel (cPanel, phpMyAdmin)

1. **Log into your hosting control panel**
2. **Open phpMyAdmin**
3. **Select your WordPress database**
4. **Click "Export" tab**
5. **Choose export method:**
   - **Quick:** Default settings
   - **Custom:** More control over what to export
6. **Click "Go"** to download the SQL file

#### Option B: Using Command Line (SSH)

```bash
# SSH into your server
ssh username@your-server.com

# Export database
mysqldump -u database_user -p database_name > wordpress_export.sql

# Download the file
# (Use SCP or download via SFTP)
```

### Step 2: Prepare the SQL File

1. **Copy the SQL file** to your project directory
2. **Edit the SQL file** (optional but recommended):
   - Search and replace: `https://blog.davidhoang.com` → `http://localhost:8080`
   - Use a text editor or command line:
     ```bash
     sed -i '' 's|https://blog.davidhoang.com|http://localhost:8080|g' wordpress_export.sql
     ```

### Step 3: Import to Local Database

1. **Ensure containers are running:**
   ```bash
   docker compose up -d
   ```

2. **Import the SQL file:**
   ```bash
   docker compose exec -T db mysql -u wordpress -pwordpress wordpress < wordpress_export.sql
   ```

   Or if you need to specify the file path:
   ```bash
   cat wordpress_export.sql | docker compose exec -T db mysql -u wordpress -pwordpress wordpress
   ```

### Step 4: Update WordPress URLs

After database import, update URLs in the database:

#### Using WP-CLI (if available in container):

```bash
# Access WordPress container
docker compose exec wordpress bash

# Update URLs
wp search-replace 'https://blog.davidhoang.com' 'http://localhost:8080' --allow-root
wp search-replace 'https://www.blog.davidhoang.com' 'http://localhost:8080' --allow-root

# Flush rewrite rules
wp rewrite flush --allow-root
```

#### Using MySQL directly:

```bash
# Access MySQL
docker compose exec db mysql -u wordpress -pwordpress wordpress

# Run SQL commands
UPDATE wp_options SET option_value = 'http://localhost:8080' WHERE option_name = 'home';
UPDATE wp_options SET option_value = 'http://localhost:8080' WHERE option_name = 'siteurl';
UPDATE wp_posts SET post_content = REPLACE(post_content, 'https://blog.davidhoang.com', 'http://localhost:8080');
UPDATE wp_posts SET guid = REPLACE(guid, 'https://blog.davidhoang.com', 'http://localhost:8080');
UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, 'https://blog.davidhoang.com', 'http://localhost:8080');
```

## Method 3: Using WP-CLI (If Available on Live Site)

If WP-CLI is installed on your live server:

### Step 1: Export from Live Site

```bash
# SSH into your server
ssh username@your-server.com

# Navigate to WordPress root
cd /path/to/wordpress

# Export database
wp db export wordpress_export.sql

# Download the file
exit
scp username@your-server.com:/path/to/wordpress/wordpress_export.sql ./
```

### Step 2: Import to Local

```bash
# Ensure containers are running
docker compose up -d

# Import database
docker compose exec wordpress wp db import wordpress_export.sql --allow-root

# Update URLs
docker compose exec wordpress wp search-replace 'https://blog.davidhoang.com' 'http://localhost:8080' --allow-root

# Flush rewrite rules
docker compose exec wordpress wp rewrite flush --allow-root
```

## Importing Media Files

Media files (images, videos, etc.) need to be imported separately if using database import:

### Option 1: Via WordPress Export/Import

The WordPress Export/Import method (Method 1) automatically downloads media files if you check "Download and import file attachments" during import.

### Option 2: Manual Media Import

1. **Download wp-content/uploads folder from live site:**
   ```bash
   scp -r username@your-server.com:/path/to/wordpress/wp-content/uploads ./wp-content/
   ```

2. **Set correct permissions:**
   ```bash
   docker compose exec wordpress chown -R www-data:www-data /var/www/html/wp-content/uploads
   ```

### Option 3: Using rsync (Efficient for Large Media Libraries)

```bash
rsync -avz --progress username@your-server.com:/path/to/wordpress/wp-content/uploads/ ./wp-content/uploads/
```

## Verifying the Import

1. **Check posts and pages:**
   - Visit http://localhost:8080
   - Check that posts and pages are visible

2. **Check media:**
   - Go to: Media → Library
   - Verify images are present and display correctly

3. **Check theme:**
   - Ensure theme is displaying correctly
   - Check for broken images or links

4. **Check admin:**
   - Verify you can log into admin
   - Check that settings are imported (if using database import)

## Troubleshooting

### Import Fails or Times Out

1. **Increase PHP limits** (if using WordPress import):
   - Edit `docker-compose.yml` to add PHP configuration
   - Or increase upload size limits

2. **Import in smaller chunks:**
   - Export specific content types
   - Import one at a time

### Images Not Displaying

1. **Check file permissions:**
   ```bash
   docker compose exec wordpress ls -la /var/www/html/wp-content/uploads
   ```

2. **Regenerate thumbnails:**
   - Install "Regenerate Thumbnails" plugin
   - Run regeneration

3. **Check URLs in database:**
   - Ensure all URLs are updated to `http://localhost:8080`

### Broken Links

1. **Run search and replace again:**
   - Some URLs might be serialized in the database
   - Use a plugin that handles serialized data

2. **Check .htaccess:**
   - Permalink structure might need updating
   - Go to: Settings → Permalinks → Save (no changes needed)

### Database Connection Errors After Import

1. **Verify database credentials:**
   - Check `.env` file matches `docker-compose.yml`

2. **Restart containers:**
   ```bash
   docker compose restart
   ```

## Security Considerations

**Important:** After importing from production:

1. **Change admin passwords:**
   - Go to: Users → Your Profile
   - Update your password

2. **Review user accounts:**
   - Remove or disable unnecessary users
   - Change all user passwords

3. **Check for sensitive data:**
   - Review imported content for API keys, credentials
   - Remove or replace with test values

4. **Update security keys:**
   - Generate new keys at: https://api.wordpress.org/secret-key/1.1/salt/
   - Update `wp-config.php` if using one

## Next Steps

After successfully importing content:

1. **Test your theme** with the imported content
2. **Start developing** - make changes to theme files
3. **Test functionality** - ensure everything works locally
4. **Make backups** before making significant changes

## Additional Resources

- [WordPress Import Documentation](https://wordpress.org/support/article/importing-content/)
- [WP-CLI Documentation](https://wp-cli.org/)
- [WordPress Database Schema](https://codex.wordpress.org/Database_Description)
