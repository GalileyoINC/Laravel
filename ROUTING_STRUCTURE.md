# Galileyo Routing Structure

## 📋 Преглед

Aplikacijata ima **dva oddelni frontend-a** koi rabotat zaedno:

1. **Vue.js SPA** - Javna aplikacija (root `/`)
2. **Laravel Blade Admin Panel** - Admin panel (prefix `/admin`)

## 🌐 URL Struktura

### Vue.js Frontend (Public)
- **Root**: `http://localhost/`
- **Login**: `http://localhost/login`
- **Dashboard**: `http://localhost/dashboard`
- **Profile**: `http://localhost/profile`
- **Bookmarks**: `http://localhost/bookmarks`
- **Blog**: `http://localhost/blog`
- **Contact**: `http://localhost/contact`
- **FAQ**: `http://localhost/faq`
- **Privacy**: `http://localhost/privacy-policy`
- **Terms**: `http://localhost/terms-of-service`
- **Alerts Map**: `http://localhost/alerts-map`

### Admin Panel (Laravel Blade)
- **Login**: `http://localhost/admin/login` ✅ PUBLIC (bez auth)
- **Dashboard**: `http://localhost/admin` 🔒 AUTH
- **Staff**: `http://localhost/admin/staff` 🔒 AUTH
- **Users**: `http://localhost/admin/user` 🔒 AUTH
- **Reports**: `http://localhost/admin/report/*` 🔒 AUTH
- **Settings**: `http://localhost/admin/settings` 🔒 AUTH
- **Email Templates**: `http://localhost/admin/email-template` 🔒 AUTH
- **Products**: `http://localhost/admin/product/*` 🔒 AUTH
- **SMS Pool**: `http://localhost/admin/sms-pool` 🔒 AUTH
- **Invoices**: `http://localhost/admin/invoice` 🔒 AUTH
- **Logs**: `http://localhost/admin/logs/*` 🔒 AUTH
- **Maintenance**: `http://localhost/admin/maintenance` 🔒 AUTH
- ... i site drugi admin ruti

### API (za Vue.js)
- **Base**: `http://localhost/api`
- **Auth**: `http://localhost/api/v1/auth/login`
- **User**: `http://localhost/api/user` (Sanctum)
- **News**: `http://localhost/api/v1/news/*`
- **Feed**: `http://localhost/api/v1/feed/*`
- **Profile**: `http://localhost/api/v1/customer/*`
- **Comments**: `http://localhost/api/v1/comment/*`
- **Bookmarks**: `http://localhost/api/v1/bookmark/*`
- ... i site drugi API endpoints

## 🔐 Authentication

### Vue.js Frontend
- Koristi **Laravel Sanctum** za API autentikacija
- Token se chuvaat vo `localStorage`
- Login endpoint: `POST /api/v1/auth/login`

### Admin Panel
- Koristi **Laravel Session** autentikacija
- Login endpoint: `POST /admin/login`
- Protected so `auth` middleware

## 🐳 Docker Ports

- **Nginx**: `80:80` (HTTP)
- **MySQL**: `3307:3306` (Database)
- **Redis**: `6380:6379` (Cache)

## 📁 File Structure

```
routes/
├── web.php          # Admin panel ruti (pod /admin prefix)
├── api.php          # API ruti za Vue.js (pod /api prefix)

resources/
├── js/
│   ├── app.js       # Vue.js entry point
│   ├── router/      # Vue Router (client-side routing)
│   ├── components/  # Vue komponenti
│   └── api/         # API client
├── views/
│   ├── app.blade.php    # Vue SPA shell
│   └── [admin views]    # Blade templates za admin

.docker/
├── nginx/
│   └── default.conf     # Nginx routing config
├── php/
│   └── conf.d/          # PHP config
└── mysql/
    └── my.cnf           # MySQL config
```

## 🚀 Kako da startuvash

```bash
# Brzo startuvanje
./docker-start.sh

# Ili ruchno
docker-compose up -d --build
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build
docker-compose exec app php artisan migrate --force
```

## ✅ Testiranje

1. **Vue Frontend**: Otvori `http://localhost/` - treba da vidish Vue homepage
2. **Vue Login**: Otvori `http://localhost/login` - treba da vidish Vue login page
3. **Admin Login**: Otvori `http://localhost/admin/login` - treba da vidish Laravel Blade login
4. **API Test**: `curl http://localhost/api/v1/auth/test` - treba da vrati JSON

## 🔧 Nginx Routing

Nginx e konfigurirano da:
- Servira **static assets** direktno (`/build`, `.js`, `.css`, images)
- Pravi **proxy** do PHP-FPM za site `.php` fajlovi
- Pravi **fallback** do `index.php` za site drugi ruti (Laravel routing)

Laravel potoa odluchuva:
- `/admin/*` → Web controllers (Blade)
- `/api/*` → API controllers (JSON)
- `/*` → Vue SPA catch-all (app.blade.php)
