# david-hoang-blog-wp-theme

Custom WordPress theme development for [davidhoang.com](https://davidhoang.com).

The active theme is **dh** (`wp-content/themes/dh/`). A local WordPress instance runs in Docker for testing.

## Quick start

```bash
cp .env.example .env   # optional
npm run up
open http://localhost:8080
```

Complete the WordPress install wizard, then activate the **dh** theme under **Appearance -> Themes**.

## Documentation

- **[DEVELOPMENT.md](./DEVELOPMENT.md)** — local setup, daily workflow, troubleshooting

## Theme status

This repo tracks the **dh** theme and the small amount of local tooling needed to develop it. WordPress uploads, plugins, imports, packaged zips, and older theme references are local-only and ignored by Git.

## Commands

```bash
npm run up      # start WordPress
npm run down    # stop containers
npm run logs    # tail logs
npm run reset   # wipe database and start fresh
npm run dev     # live reload while editing theme files
```
