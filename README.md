# david-hoang-blog-wp-theme

Custom WordPress theme development for [davidhoang.com](https://davidhoang.com).

The active theme is **dh** (`wp-content/themes/dh/`). A local WordPress instance runs in Docker for testing.

## Quick start

```bash
cp .env.example .env   # optional
npm run up
open http://localhost:8080
```

Complete the WordPress install wizard, then activate the **dh** theme under **Appearance → Themes**.

## Documentation

- **[DEVELOPMENT.md](./DEVELOPMENT.md)** — local setup, daily workflow, troubleshooting
- [DOCKER_INSTALL.md](./DOCKER_INSTALL.md) — Docker Desktop install guide
- [CONTENT_IMPORT.md](./CONTENT_IMPORT.md) — importing posts from a live site

## Theme status

The `dh` theme is a minimal foundation (style.css, functions.php, header/footer, index). The older `david-hoang` theme in `wp-content/themes/david-hoang/` remains as reference material from earlier work.

## Commands

```bash
npm run up      # start WordPress
npm run down    # stop containers
npm run logs    # tail logs
npm run reset   # wipe database and start fresh
npm run dev     # live reload while editing theme files
```
