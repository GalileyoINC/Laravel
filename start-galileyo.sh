#!/bin/bash

# Galileyo Laravel Docker Setup Script for Debian Server
# Usage: ./start-galileyo.sh <SERVER_IP>

set -e

# Check if SERVER_IP is provided
if [ -z "$1" ]; then
    echo "❌ Error: Please provide server IP address"
    echo "Usage: $0 <SERVER_IP>"
    echo "Example: $0 192.168.1.100"
    exit 1
fi

SERVER_IP="$1"

echo "🚀 Starting Galileyo Laravel application on Debian server..."
echo "📍 Server IP: $SERVER_IP"
echo "🌐 Application will be available at:"
echo "   - Web App: http://$SERVER_IP/"
echo "   - Admin Panel: http://$SERVER_IP/admin"
echo ""

# Set environment variable for docker-compose
export SERVER_IP

# Create docker-compose override for production
cat > docker-compose.override.yml << EOF
services:
  nginx:
    ports:
      - "80:80"
  app:
    environment:
      - APP_URL=http://$SERVER_IP
      - VITE_APP_URL=http://$SERVER_IP
EOF

echo "📦 Pulling latest images..."
docker compose pull

echo "🏗️ Building application..."
docker compose build

echo "🗄️ Starting services..."
docker compose up -d

echo "⏳ Waiting for services to be ready..."
sleep 10

echo "🔧 Running database migrations and seeding..."
docker compose exec app php artisan migrate:fresh --seed

echo "📦 Building frontend assets..."
docker compose exec app npm run build

echo ""
echo "✅ Galileyo application is now running!"
echo ""
echo "🌐 Access URLs:"
echo "   - Web Application: http://$SERVER_IP/"
echo "   - Admin Panel: http://$SERVER_IP/admin"
echo ""
echo "👤 Default admin credentials:"
echo "   - Email: admin@galileyo.com"
echo "   - Password: pass"
echo ""
echo "🔧 Useful commands:"
echo "   - View logs: docker compose logs -f"
echo "   - Restart: docker compose restart"
echo "   - Stop: docker compose down"
echo "   - Update: docker compose pull && docker compose up -d"
echo ""
echo "🎉 Setup complete!"
