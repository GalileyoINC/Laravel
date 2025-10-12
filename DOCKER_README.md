# Galileyo Docker Setup

Овој Docker setup овозможува да работиш со Laravel и Vue.js на порта 8001.

## 🚀 Брзо Стартување

```bash
# Стартувај го Docker environment-от
./docker-start.sh
```

## 📋 Ручни Чекори

Ако сакаш да го стартуваш рачно:

```bash
# 1. Стартувај ги контејнерите
docker-compose up -d --build

# 2. Инсталирај PHP dependencies
docker-compose exec app composer install

# 3. Инсталирај Node.js dependencies
docker-compose exec app npm install

# 4. Build Vue.js assets
docker-compose exec app npm run build

# 5. Генерирај application key
docker-compose exec app php artisan key:generate

# 6. Стартувај миграции
docker-compose exec app php artisan migrate --force

# 7. Seed базата
docker-compose exec app php artisan db:seed --force
```

## 🌐 Пристапни URL-а

-   **Frontend (Vue.js)**: http://localhost:8001
-   **API (Laravel)**: http://localhost:8001/api
-   **Database**: localhost:3307
-   **Redis**: localhost:6380

## 🛠️ Корисни Команди

```bash
# Види ги логовите
docker-compose logs -f

# Влези во контејнерот
docker-compose exec app bash

# Стартувај Artisan команди
docker-compose exec app php artisan [command]

# Стартувај npm команди
docker-compose exec app npm [command]

# Стани ги контејнерите
docker-compose down

# Стани и избриши volumes
docker-compose down -v
```

## 📁 Структура

```
galileyo_network/
├── galileyo_app (PHP-FPM + Node.js)
├── galileyo_nginx (Nginx)
├── galileyo_mysql (MariaDB)
└── galileyo_redis (Redis)
```

## 🔧 Конфигурација

-   **PHP**: 8.4-fpm-alpine
-   **Node.js**: Latest (Alpine)
-   **Nginx**: Alpine
-   **Database**: MariaDB Latest
-   **Cache**: Redis 7

## 🐛 Debugging

Ако имаш проблеми:

1. Провери ги логовите: `docker-compose logs -f`
2. Провери дали сите сервиси се здрави: `docker-compose ps`
3. Рестартирај ги сервисите: `docker-compose restart`
4. Избриши и рекреирај: `docker-compose down -v && ./docker-start.sh`
