#!/bin/bash

# Galileyo Docker Setup Script
echo "🚀 Starting Galileyo Docker Environment..."

# Stop any existing containers
echo "🛑 Stopping existing containers..."
docker compose down

# Build and start containers
echo "🔨 Building and starting containers..."
docker compose up -d --build

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 10

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
docker compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
docker compose exec app npm install

# Build Vue.js assets
echo "🎨 Building Vue.js assets..."
docker compose exec app npm run build

# Generate application key if needed
echo "🔑 Generating application key..."
docker compose exec app php artisan key:generate

# Run database migrations
echo "🗄️ Running database migrations..."
docker compose exec app php artisan migrate --force

# Seed database if needed
echo "🌱 Seeding database..."
docker compose exec app php artisan db:seed --force

# Set proper permissions (storage dirs already created in Dockerfile)
echo "🔐 Setting permissions..."
docker compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "✅ Galileyo Docker Environment is ready!"
echo "🌐 Vue Frontend: http://localhost"
echo "🔐 Admin Panel: http://localhost/admin/login"
echo "🔌 API: http://localhost/api"
echo "📊 Database: localhost:3307"
echo "🔴 Redis: localhost:6380"
echo ""
echo "To view logs: docker compose logs -f"
echo "To stop: docker compose down"
