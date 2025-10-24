# Galileyo Laravel Application

<p align="center">
<img src="public/galileyo_new_logo.png" width="200" alt="Galileyo Logo">
</p>

<p align="center">
<a href="https://github.com/KalimeroMK/GalileyoLaravel/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Galileyo

Galileyo is a comprehensive Laravel application built with modern architecture patterns, featuring a robust API backend with complete Swagger documentation, Vue.js frontend, and Docker containerization for seamless development and deployment.

## 🏗️ Architecture & Patterns

### Domain-Driven Design (DDD)
The application follows **Domain-Driven Design** principles with clear separation of concerns:

- **Domain Layer**: Contains business logic, actions, DTOs, and services
- **Application Layer**: HTTP controllers, requests, and resources
- **Infrastructure Layer**: Database, external services, and configurations

### Key Architectural Components

```
app/
├── Domain/
│   ├── Actions/          # Business logic actions
│   ├── DTOs/            # Data Transfer Objects
│   ├── Services/        # Domain services
│   └── Exceptions/      # Domain exceptions
├── Http/
│   ├── Controllers/     # API controllers
│   ├── Requests/        # Form validation
│   └── Resources/       # API resources
└── Models/              # Eloquent models
```

### Technology Stack

- **Backend**: Laravel 12 with PHP 8.3
- **Frontend**: Vue.js 3 with Tailwind CSS 4
- **Database**: MySQL with Redis for caching
- **API Documentation**: Swagger/OpenAPI 3.0
- **Containerization**: Docker with Docker Compose
- **Testing**: PHPUnit with comprehensive test coverage

## 🚀 Quick Start

### Prerequisites

- Docker and Docker Compose
- Git

### Installation & Setup

1. **Clone the repository**
```bash
git clone https://github.com/KalimeroMK/GalileyoLaravel.git
cd GalileyoLaravel
```

2. **Start Docker environment**
```bash
./docker-start.sh
```

3. **Install dependencies**
```bash
docker exec galileyo-app composer install
docker exec galileyo-app npm install
```

4. **Build frontend assets**
```bash
docker exec galileyo-app npm run build
```

5. **Run database migrations and seeders**
```bash
docker exec galileyo-app php artisan migrate --seed
```

## 🐳 Docker Configuration

### Services & Ports

| Service | Port | Description |
|---------|------|-------------|
| **Nginx** | 80 | Web server |
| **MySQL** | 3306 | Database |
| **Redis** | 6379 | Cache & sessions |
| **Laravel App** | 9000 | PHP-FPM |

### Docker Commands

```bash
# Start all services
./docker-start.sh

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# Access application container
docker exec -it galileyo-app bash

# Run Artisan commands
docker exec galileyo-app php artisan [command]
```

## 🔐 Authentication & Access

### Test User Credentials

| Role | Email | Password | Description |
|------|-------|----------|-------------|
| **Admin** | admin@galileyo.com | password | Full system access |
| **User** | test@galileyo.com | password | Regular user access |
| **Influencer** | influencer@galileyo.com | password | Influencer account |

### API Access

- **Base URL**: `http://localhost/api/v1/`
- **Authentication**: Laravel Sanctum tokens
- **Content-Type**: `application/json`

## 📚 API Documentation

### Swagger UI Access

**URL**: http://localhost/api/documentation

The application features **complete Swagger documentation** for all 28 API controllers with:

- ✅ **27 documented endpoints**
- ✅ **Real authentication examples**
- ✅ **Request/response schemas**
- ✅ **Error handling examples**
- ✅ **Interactive testing interface**

### API Endpoints Overview

| Controller | Endpoints | Description |
|------------|-----------|-------------|
| **AuthController** | 4 | Authentication & user management |
| **NewsController** | 12 | News & content management |
| **SubscriptionController** | 9 | Feed subscriptions |
| **DeviceController** | 4 | Device management |
| **OrderController** | 3 | Order & payment processing |
| **ReportController** | 6 | Analytics & reporting |
| **SettingsController** | 5 | System settings |
| **StaffController** | 5 | Staff management |
| **+ 20 more controllers** | 50+ | Complete API coverage |

## 🎨 Frontend Technology

### Vue.js 3 Frontend
- **Framework**: Vue.js 3 with Composition API
- **Styling**: Tailwind CSS 4
- **Build Tool**: Vite
- **State Management**: Vuex/Pinia (as needed)

### Blade Templates
- **Backend Views**: Laravel Blade with Bootstrap 5
- **Admin Panel**: Custom admin interface
- **Responsive Design**: Mobile-first approach

### Frontend Development

```bash
# Development mode with hot reload
docker exec galileyo-app npm run dev

# Production build
docker exec galileyo-app npm run build

# Watch for changes
docker exec galileyo-app npm run watch
```

## 🗄️ Database

### Database Configuration
- **Engine**: MySQL 8.0
- **Database**: `galileyo`
- **Charset**: `utf8mb4_unicode_ci`

### Key Features
- **Migrations**: Version-controlled schema changes
- **Seeders**: Pre-populated test data
- **Factories**: Model factories for testing
- **Eloquent ORM**: Advanced relationships and queries

### Database Commands

```bash
# Run migrations
docker exec galileyo-app php artisan migrate

# Rollback migrations
docker exec galileyo-app php artisan migrate:rollback

# Seed database
docker exec galileyo-app php artisan db:seed

# Fresh migration with seeding
docker exec galileyo-app php artisan migrate:fresh --seed
```

## 🧪 Testing

### Test Configuration
- **Framework**: PHPUnit 11
- **Coverage**: Unit and Feature tests
- **Browser Testing**: Laravel Dusk

### Running Tests

```bash
# Run all tests
docker exec galileyo-app php artisan test

# Run specific test file
docker exec galileyo-app php artisan test tests/Feature/AuthTest.php

# Run with coverage
docker exec galileyo-app php artisan test --coverage
```

## 🔧 Development Tools

### Code Quality
- **Linting**: Laravel Pint (PSR-12)
- **Static Analysis**: PHPStan
- **Code Formatting**: Automatic formatting on save

### Useful Commands

```bash
# Format code
docker exec galileyo-app vendor/bin/pint

# Clear caches
docker exec galileyo-app php artisan optimize:clear

# Generate Swagger docs
docker exec galileyo-app php artisan l5-swagger:generate

# Queue processing
docker exec galileyo-app php artisan queue:work
```

## 📁 Project Structure

```
GalileyoLaravel/
├── app/
│   ├── Domain/              # DDD Domain layer
│   ├── Http/                # HTTP layer
│   ├── Models/              # Eloquent models
│   └── Services/            # Application services
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── js/                  # Vue.js components
│   ├── css/                 # Stylesheets
│   └── views/               # Blade templates
├── routes/
│   ├── api.php              # API routes
│   └── web.php              # Web routes
├── tests/                   # Test suites
├── docker-compose.yml       # Docker configuration
└── docker-start.sh         # Docker startup script
```

## 🌐 Environment Configuration

### Key Environment Variables

```env
APP_NAME=Galileyo
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=galileyo
REDIS_HOST=redis
REDIS_PORT=6379
```

## 📈 Performance & Optimization

### Caching Strategy
- **Redis**: Session storage and caching
- **Laravel Cache**: Application-level caching
- **Database**: Query optimization with indexes

### Production Considerations
- **Queue Workers**: Background job processing
- **CDN**: Static asset delivery
- **Database**: Read replicas for scaling
- **Monitoring**: Application performance tracking

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Use meaningful commit messages

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- **Documentation**: Check Swagger UI at http://localhost/api/documentation
- **Issues**: Create an issue on GitHub
- **Email**: Contact the development team

---

**Built with ❤️ using Laravel 12, Vue.js 3, and Docker**