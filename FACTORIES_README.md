# 🏭 Laravel Factories & Demo Data

Оваа документација објаснува како да користиш Laravel factories за креирање на demo data за Galileyo апликацијата.

## 📋 Достапни Factories

### 👤 UserFactory
Креира корисници со реалистични податоци.

```php
// Основни корисници
User::factory(10)->create();

// Influencer корисници
User::factory(5)->influencer()->create();

// Верификувани корисници
User::factory(10)->verified()->create();

// Test корисници
User::factory(3)->test()->create();
```

### 📰 SubscriptionFactory
Креира subscriptions за различни категории.

```php
// Основни subscriptions
Subscription::factory(20)->create();

// Активни subscriptions
Subscription::factory(10)->active()->create();

// Јавни subscriptions
Subscription::factory(15)->public()->create();

// Marketstack subscriptions
Subscription::factory(5)->marketstack()->create();
```

### 📰 NewsFactory
Креира вести со различни статуси.

```php
// Основни вести
News::factory(50)->create();

// Објавени вести
News::factory(30)->published()->create();

// Draft вести
News::factory(10)->draft()->create();

// Вести со слики
News::factory(20)->withImage()->create();
```

### 💬 CommentFactory
Креира коментари за вести.

```php
// Основни коментари
Comment::factory(100)->create();

// Reply коментари
Comment::factory(50)->reply()->create();

// Неодамнешни коментари
Comment::factory(30)->recent()->create();
```

### 💳 CreditCardFactory
Креира кредитни карти со различни типови.

```php
// Основни карти
CreditCard::factory(25)->create();

// Visa карти
CreditCard::factory(10)->visa()->create();

// MasterCard карти
CreditCard::factory(10)->mastercard()->create();

// Преферирани карти
CreditCard::factory(5)->preferred()->create();
```

### 📱 DeviceFactory
Креира уреди за корисници.

```php
// Основни уреди
Device::factory(30)->create();

// iOS уреди
Device::factory(15)->ios()->create();

// Android уреди
Device::factory(15)->android()->create();

// Уреди со push notifications
Device::factory(20)->pushEnabled()->create();
```

### 📨 SmsPoolFactory
Креира SMS пораки.

```php
// Основни SMS
SmsPool::factory(50)->create();

// Испратени SMS
SmsPool::factory(30)->sent()->create();

// Закажани SMS
SmsPool::factory(10)->scheduled()->create();

// News SMS
SmsPool::factory(20)->news()->create();
```

### 👥 FollowerListFactory
Креира листи на следбеници.

```php
// Основни листи
FollowerList::factory(20)->create();

// Активни листи
FollowerList::factory(15)->active()->create();

// Листи со слики
FollowerList::factory(10)->withImage()->create();
```

### 🌟 InfluencerPageFactory
Креира страници за инфлуенсери.

```php
// Основни страници
InfluencerPage::factory(15)->create();

// Страници со слики
InfluencerPage::factory(10)->withImage()->create();
```

## 🚀 Брзо стартување

### 1. Креирај demo data
```bash
php artisan demo:setup
```

### 2. За fresh setup (брише постоечки податоци)
```bash
php artisan demo:setup --fresh
```

### 3. Стартувај серверот
```bash
php artisan serve
```

## 📊 Demo корисници

По креирање на demo data, ќе имаш следниве корисници:

- **Admin**: `admin@galileyo.com` (password: `password`)
- **Test**: `test@galileyo.com` (password: `password`)  
- **Influencer**: `influencer@galileyo.com` (password: `password`)

## 🔧 Прилагодување на Factories

Можеш да ги прилагодиш factories за твои потреби:

```php
// Во factory фајлот
public function definition(): array
{
    return [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        // ... други полиња
    ];
}

// Додај нови states
public function premium(): static
{
    return $this->state(fn (array $attributes) => [
        'is_premium' => true,
        'plan' => 'premium',
    ]);
}
```

## 📈 Статистики

По креирање на demo data ќе имаш:

- **50+ корисници** со различни улоги
- **30+ subscriptions** за различни категории
- **100+ вести** со коментари
- **75+ кредитни карти** за тестирање
- **60+ уреди** за push notifications
- **150+ SMS пораки** за различни цели
- **40+ листи на следбеници**
- **25+ инфлуенсер страници**

## 🎯 API тестирање

Користи ги demo корисниците за тестирање на API endpoints:

```bash
# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@galileyo.com","password":"password"}'

# Get profile
curl -X POST http://localhost:8000/api/v1/customer/get-profile \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🔄 Ресетирање на податоци

За да ги ресетираш податоците:

```bash
# Брише сите табели и ги креира повторно
php artisan demo:setup --fresh

# Или само ги ресетира податоците
php artisan migrate:fresh --seed
```

## 📝 Забелешки

- Сите factories користат `fake()` helper за реалистични податоци
- Податоците се креираат со правилни релации помеѓу моделите
- Timestamps се поставуваат реалистично (последни 1-2 години)
- Email адреси се уникатни за да избегнеш конфликти
- Паролите се хеширани со Laravel Hash facade

## 🆘 Проблеми

Ако имаш проблеми:

1. Провери дали си во правилниот директориум (`GalileyoLaravel/`)
2. Провери дали имаш правилни database credentials во `.env`
3. Провери дали си ги стартувал миграциите
4. Провери дали имаш доволно меморија за креирање на голем број записи

За дополнителна помош, провери Laravel документацијата за [Database Factories](https://laravel.com/docs/10.x/database-testing#defining-model-factories).
