# Galileyo Laravel - Debian Server Setup

## Quick Start

1. **Copy the project to your Debian server**
2. **Run the setup script:**
   ```bash
   ./start-galileyo.sh <YOUR_SERVER_IP>
   ```
   
   Example:
   ```bash
   ./start-galileyo.sh 192.168.1.100
   ```

## What the script does

- Sets up Docker containers for Laravel app, MySQL, Redis, and Nginx
- Maps the application to port 80 (no SSL/HTTPS)
- Configures APP_URL to use your server IP
- Runs database migrations and seeding
- Builds frontend assets
- Creates admin user

## Access URLs

- **Web Application**: `http://<YOUR_SERVER_IP>/`
- **Admin Panel**: `http://<YOUR_SERVER_IP>/admin`

## Default Admin Credentials

- **Email**: `admin@galileyo.com`
- **Password**: `pass`

## Useful Commands

```bash
# View application logs
docker compose logs -f

# Restart all services
docker compose restart

# Stop all services
docker compose down

# Update and restart
docker compose pull && docker compose up -d

# Access app container
docker compose exec app bash

# Run artisan commands
docker compose exec app php artisan <command>
```

## Requirements

- Docker and Docker Compose installed on Debian
- Port 80 available (no other web server running)
- At least 2GB RAM recommended

## Troubleshooting

If you encounter issues:

1. Check if port 80 is available:
   ```bash
   sudo netstat -tlnp | grep :80
   ```

2. Check Docker logs:
   ```bash
   docker compose logs
   ```

3. Restart services:
   ```bash
   docker compose restart
   ```
