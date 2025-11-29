# Docker Desktop Installation Guide for macOS

## Step-by-Step Installation

### 1. Download Docker Desktop

1. Visit the official Docker Desktop download page:
   - **Apple Silicon (M1/M2/M3)**: https://www.docker.com/products/docker-desktop/
   - **Intel Mac**: https://www.docker.com/products/docker-desktop/

2. Click "Download for Mac" and select the appropriate version for your processor

### 2. Install Docker Desktop

1. Open the downloaded `.dmg` file
2. Drag the Docker icon to your Applications folder
3. Open Docker Desktop from Applications (or Launchpad)
4. You may be prompted to enter your password to install networking components

### 3. Initial Setup

1. **First Launch:**
   - Docker Desktop will ask for system permissions
   - Grant necessary permissions when prompted
   - Wait for Docker to start (the Docker icon will appear in your menu bar)

2. **Optional: Sign in to Docker Hub**
   - You can skip this for local development
   - Signing in allows you to pull private images

### 4. Verify Installation

Open Terminal and run:

```bash
docker --version
```

You should see output like:
```
Docker version 24.0.0, build abc123
```

Verify Docker Compose:

```bash
docker compose version
```

You should see output like:
```
Docker Compose version v2.20.0
```

### 5. Test Docker

Run a test container to ensure everything works:

```bash
docker run hello-world
```

You should see a success message confirming Docker is working correctly.

## System Requirements

- **macOS**: 10.15 or newer
- **RAM**: Minimum 4GB (8GB+ recommended)
- **Disk Space**: At least 10GB free space
- **Virtualization**: Enabled (usually automatic on modern Macs)

## Troubleshooting

### Docker Desktop Won't Start

1. **Check System Requirements:**
   - Ensure your Mac meets the minimum requirements
   - Check if virtualization is enabled in System Settings

2. **Restart Docker Desktop:**
   - Quit Docker Desktop completely
   - Restart from Applications

3. **Reset Docker Desktop:**
   - Docker Desktop → Settings → Troubleshoot → Reset to factory defaults
   - **Warning:** This will remove all containers and images

### Permission Denied Errors

If you see permission errors:

1. Ensure Docker Desktop is running
2. Check that your user is in the `docker` group (usually automatic on Mac)
3. Try restarting Docker Desktop

### High CPU/Memory Usage

Docker Desktop uses system resources. To limit usage:

1. Docker Desktop → Settings → Resources
2. Adjust CPU and Memory limits
3. For WordPress development, 2-4 CPUs and 4GB RAM is usually sufficient

### Port Conflicts

If you see port binding errors:

1. Check what's using the port:
   ```bash
   lsof -i :8080
   ```
2. Either stop the conflicting service or change the port in `.env`

## Next Steps

Once Docker is installed and verified:

1. Navigate to your project directory
2. Run `docker compose up -d` to start WordPress
3. See the main README.md for usage instructions

## Additional Resources

- [Docker Desktop for Mac Documentation](https://docs.docker.com/desktop/install/mac-install/)
- [Docker Desktop User Guide](https://docs.docker.com/desktop/)
- [Docker Community Forums](https://forums.docker.com/)
