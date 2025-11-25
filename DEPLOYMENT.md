# Slice Deployment Guide

Complete guide for deploying Slice in different environments.

---

## Table of Contents

- [Local Development](#local-development)
- [External Access (ngrok)](#external-access-ngrok)
- [Production Server](#production-server)
- [Environment Configuration](#environment-configuration)
- [Troubleshooting](#troubleshooting)

---

## Local Development

### Quick Start

```bash
# 1. Start Laravel server
php artisan serve

# 2. Start Reverb WebSocket server (new terminal)
php artisan reverb:start

# 3. Start Vite dev server (new terminal, optional)
npm run dev
```

Access at: `http://localhost:8000`

### Local Environment Variables

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

---

## External Access (ngrok)

Suitable for demos, testing, or sharing with clients.

### Step 1: Install ngrok

Download from [ngrok.com](https://ngrok.com/) or use package manager:

```bash
# Windows (Chocolatey)
choco install ngrok

# Mac (Homebrew)
brew install ngrok

# Linux (Snap)
snap install ngrok
```

### Step 2: Start Laravel Server

```bash
php artisan serve
# Server running at http://127.0.0.1:8000
```

### Step 3: Start ngrok Tunnel

```bash
# From project root
./ngrok.exe http 8000

# Or if installed globally
ngrok http 8000
```

You'll see output like:

```
Forwarding    https://abc123.ngrok-free.dev -> http://localhost:8000
```

### Step 4: Update Environment

Update `.env` with your ngrok URL:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://abc123.ngrok-free.dev
ASSET_URL=https://abc123.ngrok-free.dev

REVERB_HOST="abc123.ngrok-free.dev"
REVERB_PORT=443
REVERB_SCHEME=https

VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### Step 5: Build Production Assets

```bash
# Remove hot reload file
rm public/hot  # Linux/Mac
del public\hot  # Windows

# Build assets
npm run build

# Cache configuration
php artisan config:cache
php artisan view:cache
```

### Step 6: Start Reverb

```bash
php artisan reverb:start
```

### Access Your Site

Share the ngrok URL with your friends/clients:

- Public URL: `https://abc123.ngrok-free.dev`
- Admin Panel: `https://abc123.ngrok-free.dev/admin/dashboard`

### Important Notes

- **Free ngrok**: URL changes on restart
- **Paid ngrok**: Custom subdomain, always same URL
- **Session issue**: Users need to refresh if URL changes
- **WebSockets**: Reverb must run on same server

---

## Production Server

### Requirements

- Ubuntu 20.04+ / CentOS 8+
- PHP 8.2+
- Nginx / Apache
- MySQL 8.0+
- Node.js 18+
- Composer
- Supervisor (for queue workers)

### Step 1: Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd -y

# Install MySQL
sudo apt install mysql-server -y

# Install Nginx
sudo apt install nginx -y

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Step 2: Clone and Setup

```bash
# Create directory
sudo mkdir -p /var/www/slice
cd /var/www/slice

# Clone repository
git clone https://github.com/aditya0w0/Slice.git .

# Set permissions
sudo chown -R www-data:www-data /var/www/slice
sudo chmod -R 755 /var/www/slice

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### Step 3: Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
ASSET_URL=https://yourdomain.com

DB_HOST=127.0.0.1
DB_DATABASE=slice
DB_USERNAME=slice_user
DB_PASSWORD=secure_password

REVERB_HOST="yourdomain.com"
REVERB_PORT=6001
REVERB_SCHEME=https
```

### Step 4: Database Setup

```bash
# Create database
mysql -u root -p
```

```sql
CREATE DATABASE slice;
CREATE USER 'slice_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON slice.* TO 'slice_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Run migrations
php artisan migrate --force
php artisan storage:link
```

### Step 5: Nginx Configuration

Create `/etc/nginx/sites-available/slice`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    root /var/www/slice/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # WebSocket support for Reverb
    location /app {
        proxy_pass http://127.0.0.1:6001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/slice /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Step 6: SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get certificate
sudo certbot --nginx -d yourdomain.com

# Auto-renewal is set up automatically
```

### Step 7: Supervisor for Reverb

Create `/etc/supervisor/conf.d/slice-reverb.conf`:

```ini
[program:slice-reverb]
process_name=%(program_name)s
command=php /var/www/slice/artisan reverb:start
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/slice/storage/logs/reverb.log
stopwaitsecs=3600
```

Start Supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start slice-reverb
```

### Step 8: Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## Environment Configuration

### Development

```env
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug

VITE_HOT_FILE=public/hot
```

### Staging

```env
APP_ENV=staging
APP_DEBUG=true
LOG_LEVEL=info
```

### Production

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error

SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

---

## Troubleshooting

### CSS/JS Not Loading

**Problem**: Assets return 404

**Solution**:

```bash
# Remove hot file
rm public/hot

# Rebuild assets
npm run build

# Clear cache
php artisan optimize:clear
php artisan config:cache
```

### WebSocket Connection Failed

**Problem**: "Failed to connect to Reverb"

**Solution**:

```bash
# Check Reverb is running
ps aux | grep reverb

# Restart Reverb
php artisan reverb:restart

# Check .env configuration
php artisan config:clear
```

### Mixed Content Errors

**Problem**: HTTP resources on HTTPS page

**Solution**:

```env
# Force HTTPS
APP_URL=https://yourdomain.com
ASSET_URL=https://yourdomain.com
REVERB_SCHEME=https
```

### Permission Errors

**Problem**: "Permission denied" on storage

**Solution**:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Database Connection Failed

**Problem**: "Access denied for user"

**Solution**:

```bash
# Check MySQL is running
sudo systemctl status mysql

# Test connection
mysql -u slice_user -p

# Reset password if needed
mysql -u root -p
ALTER USER 'slice_user'@'localhost' IDENTIFIED BY 'new_password';
```

### 500 Internal Server Error

**Problem**: White screen with 500 error

**Solution**:

```bash
# Check logs
tail -f storage/logs/laravel.log

# Check permissions
sudo chown -R www-data:www-data .

# Clear all caches
php artisan optimize:clear

# Check .env exists
ls -la .env
```

### ngrok Session Expired

**Problem**: "Session expired" on ngrok

**Solution**:

```bash
# Restart ngrok (free tier)
./ngrok http 8000

# Update .env with NEW URL
# Rebuild assets
npm run build
php artisan config:cache
```

---

## Monitoring

### Check Application Status

```bash
# Laravel health
php artisan about

# Check queue
php artisan queue:work

# Check schedule
php artisan schedule:list

# Check routes
php artisan route:list
```

### Log Monitoring

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx access
tail -f /var/log/nginx/access.log

# Nginx errors
tail -f /var/log/nginx/error.log

# Reverb logs
tail -f storage/logs/reverb.log
```

---

## Updates & Maintenance

### Deploying Updates

```bash
# Pull latest code
git pull origin master

# Update dependencies
composer install --no-dev
npm install

# Build assets
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo supervisorctl restart slice-reverb
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
```

### Backup

```bash
# Database backup
mysqldump -u slice_user -p slice > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf files_backup_$(date +%Y%m%d).tar.gz storage public/storage
```

---

For more help, visit the [README](README.md) or create an issue on GitHub.
