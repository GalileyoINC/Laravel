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

-   **Domain Layer**: Contains business logic, actions, DTOs, and services
-   **Application Layer**: HTTP controllers, requests, and resources
-   **Infrastructure Layer**: Database, external services, and configurations

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

-   **Backend**: Laravel 12 with PHP 8.3
-   **Frontend**: Vue.js 3 with Tailwind CSS 4
-   **Database**: MySQL with Redis for caching
-   **API Documentation**: Swagger/OpenAPI 3.0
-   **Containerization**: Docker with Docker Compose
-   **Testing**: PHPUnit with comprehensive test coverage

## 🚀 Quick Start

### Prerequisites

-   Docker and Docker Compose
-   Git

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

| Service         | Port | Description      |
| --------------- | ---- | ---------------- |
| **Nginx**       | 80   | Web server       |
| **MySQL**       | 3306 | Database         |
| **Redis**       | 6379 | Cache & sessions |
| **Laravel App** | 9000 | PHP-FPM          |

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

| Role           | Email                   | Password | Description         |
| -------------- | ----------------------- | -------- | ------------------- |
| **Admin**      | admin@galileyo.com      | password | Full system access  |
| **User**       | user@galileyo.com       | password | Regular user access |
| **Influencer** | influencer@galileyo.com | password | Influencer account  |

### API Access

-   **Base URL**: `http://localhost/api/v1/`
-   **Authentication**: Laravel Sanctum tokens
-   **Content-Type**: `application/json`

## 📚 API Documentation

### Swagger UI Access

**URL**: http://localhost/api/documentation

The application features **complete Swagger documentation** for all 28 API controllers with:

-   ✅ **27 documented endpoints**
-   ✅ **Real authentication examples**
-   ✅ **Request/response schemas**
-   ✅ **Error handling examples**
-   ✅ **Interactive testing interface**

### API Endpoints Overview

| Controller                 | Endpoints | Description                      |
| -------------------------- | --------- | -------------------------------- |
| **AuthController**         | 4         | Authentication & user management |
| **PaymentController**      | 5         | Payment & credit card management |
| **NewsController**         | 12        | News & content management        |
| **SubscriptionController** | 9         | Feed subscriptions               |
| **DeviceController**       | 4         | Device management                |
| **OrderController**        | 3         | Order & payment processing       |
| **ReportController**       | 6         | Analytics & reporting            |
| **SettingsController**     | 5         | System settings                  |
| **StaffController**        | 5         | Staff management                 |
| **+ 20 more controllers**  | 50+       | Complete API coverage            |

## 🎨 Frontend Technology

### Vue.js 3 Frontend

-   **Framework**: Vue.js 3 with Composition API
-   **Styling**: Tailwind CSS 4
-   **Build Tool**: Vite
-   **State Management**: Vuex/Pinia (as needed)

### Blade Templates

-   **Backend Views**: Laravel Blade with Bootstrap 5
-   **Admin Panel**: Custom admin interface
-   **Responsive Design**: Mobile-first approach

### Frontend Development

```bash
# Development mode with hot reload
docker exec galileyo-app npm run dev

# Production build
docker exec galileyo-app npm run build

# Watch for changes
docker exec galileyo-app npm run watch
```

## 💳 Payment System

### Complete Payment Management

The application features a **comprehensive payment system** migrated from Next.js with full DDD architecture:

#### Payment Features

-   ✅ **Credit Card Management** - Full CRUD operations
-   ✅ **Payment History** - Complete transaction tracking
-   ✅ **Subscription Management** - Plan management and billing
-   ✅ **Authorize.net Integration** - Ready for production payment processing
-   ✅ **Security Features** - Masked card numbers and encrypted CVV
-   ✅ **Preferred Cards** - Set default payment methods
-   ✅ **Validation** - Comprehensive input validation

#### Payment API Endpoints

| Method   | Endpoint                                      | Description              |
| -------- | --------------------------------------------- | ------------------------ |
| `GET`    | `/api/v1/payment/credit-cards`                | List user's credit cards |
| `POST`   | `/api/v1/payment/credit-cards`                | Add new credit card      |
| `PUT`    | `/api/v1/payment/credit-cards/{id}`           | Update credit card       |
| `DELETE` | `/api/v1/payment/credit-cards/{id}`           | Delete credit card       |
| `POST`   | `/api/v1/payment/credit-cards/{id}/preferred` | Set as preferred card    |

#### Frontend Components

-   **PaymentMethods.vue** - Credit card management interface
-   **PaymentHistory.vue** - Payment history display
-   **Membership.vue** - Subscription management
-   **PaymentPage.vue** - Main payment dashboard

#### Access Payment System

**URL**: http://localhost/payment

**Authentication**: Requires user login with Sanctum token

#### Database Schema

```sql
-- Credit Cards Table
credit_cards (id, user_id, first_name, last_name, num, cvv, type,
              expiration_year, expiration_month, is_active, is_preferred,
              anet_customer_payment_profile_id, created_at, updated_at)

-- User Subscriptions Table
user_subscriptions (id, user_id, product_id, credit_card_id, status,
                    price, start_date, end_date, is_cancelled, created_at, updated_at)

-- Payment History Table
payment_histories (id, user_id, subscription_id, credit_card_id, type,
                   total, title, is_success, external_transaction_id, created_at, updated_at)
```

## 🗄️ Database

### Database Configuration

-   **Engine**: MySQL 8.0
-   **Database**: `galileyo`
-   **Charset**: `utf8mb4_unicode_ci`

### Key Features

-   **Migrations**: Version-controlled schema changes
-   **Seeders**: Pre-populated test data
-   **Factories**: Model factories for testing
-   **Eloquent ORM**: Advanced relationships and queries

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

-   **Framework**: PHPUnit 11
-   **Coverage**: Unit and Feature tests
-   **Browser Testing**: Laravel Dusk
-   **Database**: MariaDB for testing (configured in phpunit.xml)

### Running Tests

```bash
# Run all tests
docker exec galileyo-app php artisan test

# Run specific test file
docker exec galileyo-app php artisan test tests/Feature/AuthTest.php

# Run Payment System tests
docker exec galileyo-app php artisan test tests/Unit/Payment/ tests/Feature/Payment/

# Run with coverage
docker exec galileyo-app php artisan test --coverage
```

### Push Notifications System

The application features **Web Push Notifications** for real-time alerts and updates:

#### Push Notification Features

-   **Service Worker Integration** - Background notification handling
-   **VAPID Authentication** - Secure push message encryption
-   **User Subscriptions** - Per-user notification preferences
-   **Broadcast Messaging** - Send notifications to all users
-   **Notification Center** - In-app notification management

#### Push API Endpoints

| Method | Endpoint                   | Description                     |
| ------ | -------------------------- | ------------------------------- |
| `POST` | `/api/v1/push/subscribe`   | Subscribe to push notifications |
| `POST` | `/api/v1/push/unsubscribe` | Unsubscribe from notifications  |

#### Configuration

Add to your `.env` file:

```env
VAPID_EMAIL=mailto:info@galileyo.com
VAPID_PUBLIC_KEY=your_public_key_here
VAPID_PRIVATE_KEY=your_private_key_here
```

Generate VAPID keys using:

```bash
docker exec galileyo-app vendor/bin/generate-vapid-keys
```

Or use online generator: https://web-push-codelab.glitch.me/

### Payment System Test Coverage

The Payment System includes comprehensive test coverage:

#### Unit Tests (16/16 passing) ✅

-   **PaymentDetailsDTOTest** - DTO validation and conversion (4/4 tests)
-   **PaymentListRequestDTOTest** - Pagination and request handling (6/6 tests)
-   **SimplePaymentServiceTest** - Core payment business logic (6/6 tests)

#### Feature Tests (11/11 failing) ❌

-   **PaymentServiceTest** - Complex database operations (9/9 tests failing - foreign key constraints)
-   **CreditCardTest** - Credit card CRUD operations (2/2 tests failing - foreign key constraints)

#### Test Status Summary

-   **Total Tests**: 27 tests
-   **Passing**: 16 tests (59%)
-   **Failing**: 11 tests (41%)
-   **Issue**: Foreign key constraint violations in database-dependent tests
-   **Core Functionality**: ✅ DTOs and business logic fully tested
-   **API Endpoints**: ❌ Require database setup fixes

## 🔧 Development Tools

### Code Quality

-   **Linting**: Laravel Pint (PSR-12)
-   **Static Analysis**: PHPStan
-   **Code Formatting**: Automatic formatting on save

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
│   │   ├── Actions/Payment/ # Payment business logic
│   │   ├── DTOs/Payment/    # Payment data transfer objects
│   │   └── Services/Payment/ # Payment domain services
│   ├── Http/                # HTTP layer
│   │   ├── Controllers/Api/PaymentController.php
│   │   ├── Requests/Payment/ # Payment validation
│   │   └── Resources/Payment/ # Payment API resources
│   ├── Models/              # Eloquent models
│   │   ├── CreditCard.php   # Credit card model
│   │   ├── PaymentHistory.php # Payment history model
│   │   └── UserSubscription.php # Subscription model
│   └── Services/            # Application services
├── database/
│   ├── factories/           # Model factories
│   │   └── CreditCardFactory.php
│   ├── migrations/          # Database migrations
│   │   ├── create_credit_cards_table.php
│   │   ├── create_user_subscriptions_table.php
│   │   └── create_payment_histories_table.php
│   └── seeders/             # Database seeders
├── resources/
│   ├── js/                  # Vue.js components
│   │   ├── api/payment.js   # Payment API service
│   │   └── components/payment/ # Payment components
│   │       ├── PaymentMethods.vue
│   │       ├── PaymentHistory.vue
│   │       ├── Membership.vue
│   │       └── views/PaymentPage.vue
│   ├── css/                 # Stylesheets
│   └── views/               # Blade templates
├── routes/
│   ├── api.php              # API routes (includes payment routes)
│   └── web.php              # Web routes
├── tests/                   # Test suites
│   ├── Unit/Payment/        # Payment unit tests
│   └── Feature/Payment/     # Payment feature tests
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

-   **Redis**: Session storage and caching
-   **Laravel Cache**: Application-level caching
-   **Database**: Query optimization with indexes

### Production Considerations

-   **Queue Workers**: Background job processing
-   **CDN**: Static asset delivery
-   **Database**: Read replicas for scaling
-   **Monitoring**: Application performance tracking

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

-   Follow PSR-12 coding standards
-   Write tests for new features
-   Update documentation as needed
-   Use meaningful commit messages

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:

-   **Documentation**: Check Swagger UI at http://localhost/api/documentation
-   **Payment System**: Access at http://localhost/payment
-   **Issues**: Create an issue on GitHub
-   **Email**: Contact the development team

## 🚀 Recent Updates

### Latest Features & Improvements

#### Alert Map System

-   ✅ **58+ Alert Samples** with varied coordinates across the US
-   ✅ **Real-time Map Integration** with latitude/longitude tracking
-   ✅ **Multiple Alert Types**: Weather, Traffic, Security, Medical, Fire, Police, Construction, Emergency, Utility
-   ✅ **Severity Levels**: Critical, High, Medium, Low
-   ✅ **Auto-seed with DatabaseSeeder** - creates alerts automatically

#### Contact Form Enhancements

-   ✅ **Phone Field Added** to contact form
-   ✅ **Save Messages** when admin is offline - auto-converts chat to contact
-   ✅ **Email & Phone Required** for better communication

#### Live Chat System (In Progress)

-   ✅ **Admin Online Detection** - checks if admin is active
-   ✅ **Smart Message Handling** - saves to contact table if admin offline
-   ✅ **Real-time Chat** when admin is online
-   🔄 **Frontend Chat Widget** - temporarily disabled for testing

#### Admin Panel Improvements

-   ✅ **Consistent Button Sizes** - all action buttons use btn-sm
-   ✅ **Pagination Everywhere** - all index pages have pagination (50 records per page)
-   ✅ **Improved Filter Tables** - using TableFilterHelper component
-   ✅ **Better Data Display** - fixed empty records and missing relationships

#### Webhook & Notification Systems

-   ✅ **IEX Webhooks** - Full CRUD operations with delete action
-   ✅ **Apple Notifications** - complete management interface
-   ✅ **Twilio Incoming** - improved data display with proper routing

#### Product Management

-   ✅ **Product Devices** - fixed Service model relationships
-   ✅ **Product Plans** - device plan management
-   ✅ **Product Alerts** - digital alerts with coordinates
-   ✅ **Better Edit Forms** - full-page editing instead of modals

#### Database & Migrations

-   ✅ **Phone Field** added to contact table
-   ✅ **Unsubscribe Field** added to register table
-   ✅ **Credit Cards Table** - proper plural naming and relationships
-   ✅ **User Subscription Pivots** - fixed all pivot table relationships

#### Code Quality

-   ✅ **Removed all try-catch** from Action classes
-   ✅ **Removed try-catch** from Controller classes
-   ✅ **Consistent Architecture** - Actions return data, Controllers format responses
-   ✅ **Laravel Pint** - code formatting across all files

### Migration from Yii to Laravel

**Completed Modules:**

-   ✅ User Management
-   ✅ Subscription Management
-   ✅ Device Management
-   ✅ Contact & Communication
-   ✅ News & Content
-   ✅ Analytics & Reports
-   ✅ Settings & Configuration
-   ✅ Payment & Finance
-   ✅ Notification Systems
-   ✅ Email & SMS Management

**Architecture Refactoring:**

-   ✅ **Domain-Driven Design** - complete DDD implementation
-   ✅ **Action Classes** - all business logic moved to Actions
-   ✅ **DTO Pattern** - used for create/update operations
-   ✅ **Request Classes** - validation separated from controllers
-   ✅ **Resource Classes** - consistent API responses
-   ✅ **Minimal Controllers** - no business logic in controllers

**Database Refactoring:**

-   ✅ **Proper Relationships** - fixed all Eloquent relationships
-   ✅ **Pivot Tables** - corrected all many-to-many relationships
-   ✅ **Factory Data** - improved factories for demo data
-   ✅ **Migrations** - synchronized with Yii schema

---

**Built with ❤️ using Laravel 12, Vue.js 3, and Docker**
