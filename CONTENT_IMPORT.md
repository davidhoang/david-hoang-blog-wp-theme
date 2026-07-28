# Content import

How to mirror posts from a live WordPress site into your local Docker environment for theme development.

## Prerequisites

- Local environment running (`npm run up`)
- WordPress installed and the **dh** theme activated
- Admin access on the source (live) site

## Option 1: WordPress export / import (recommended)

### Export from the live site

1. Log in to the live WordPress admin.
2. Go to **Tools → Export**.
3. Choose **All content** (or **Posts** only).
4. Download the `.xml` file (WXR format).

### Import into local

1. Open http://localhost:8080/wp-admin on your local site.
2. Install the **WordPress Importer** plugin if prompted:
   - **Plugins → Add New** → search "WordPress Importer" → Install → Activate
3. Go to **Tools → Import → WordPress**.
4. Upload the `.xml` file.
5. Assign authors to your local admin user.
6. Check **Download and import file attachments** if you want images locally.

Imported posts will use your theme templates immediately. Re-run the import only on a fresh database (`npm run reset`) to avoid duplicates.

## Option 2: WP-CLI (advanced)

Shell into the WordPress container:

```bash
docker compose exec wordpress bash
```

Install WP-CLI if needed (many official WordPress images include it):

```bash
wp --info
```

Import from a WXR file copied into the container:

```bash
wp plugin install wordpress-importer --activate
wp import /var/www/html/import.xml --authors=create
```

Copy the file into the container first:

```bash
docker compose cp ./export.xml wordpress:/var/www/html/import.xml
```

## Option 3: Manual sample posts

For quick layout testing without a full import:

1. Create 3–5 posts in **Posts → Add New** with varied titles, featured images, and tags.
2. Add at least one long post with multiple headings to test reading time and block patterns.
3. Create an **About** page if you plan to use a static front page.

## Static front page setup

After importing content:

1. **Settings → Reading**
2. Set **Your homepage displays** to **A static page**
3. Choose a page for **Homepage** (e.g. About)
4. Choose a page for **Posts page** (e.g. Blog)

The theme provides `front-page.php` and `home.php` for this layout.

## Media and URLs

Imported content may still reference live URLs for images until attachments are downloaded. After import:

- Use a search-replace plugin locally if paths need updating, or
- Re-import with attachment download enabled

Never run destructive search-replace against production without a backup.

## Resetting local content

To wipe the database and start over:

```bash
npm run reset
npm run up
```

Then revisit http://localhost:8080 and complete the install wizard again before re-importing.
