# 🎯 Laravel API Resources Documentation

Оваа документација објаснува како да користиш Laravel API Resources за постојани responses во Galileyo апликацијата.

## 📋 Достапни Resources

### 🔐 **AuthenticationResource**

За authentication responses (login, register, profile).

```php
// Успешен login response
{
    "status": "success",
    "data": {
        "id": 1,
        "email": "user@example.com",
        "first_name": "John",
        "last_name": "Doe",
        "role": 2,
        "status": 1,
        "is_influencer": false,
        "is_valid_email": true,
        "bonus_point": 100,
        "image": null,
        "timezone": "UTC",
        "auth_key": "abc123...",
        "access_token": "token123...",
        "refresh_token": "refresh123...",
        "expires_at": "2024-12-31T23:59:59Z"
    },
    "meta": {
        "timestamp": "2024-01-01T12:00:00Z",
        "version": "1.0"
    }
}
```

### 💬 **ChatResource**

За chat responses (conversations, messages).

```php
// Chat list response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_user": 1,
        "name": "Chat with John",
        "type": "private",
        "status": "active",
        "last_message": "Hello!",
        "last_message_time": "2024-01-01T12:00:00Z",
        "unread_count": 2,
        "participants": [...],
        "messages": [...],
        "files": [...]
    }
}
```

### 💬 **CommentResource**

За comment responses.

```php
// Comment response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_sms_pool": 1,
        "id_user": 1,
        "message": "Great post!",
        "id_parent": null,
        "is_deleted": false,
        "user": {...},
        "replies": [...],
        "reactions": [...]
    }
}
```

### 💳 **CreditCardResource**

За credit card responses.

```php
// Credit card response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_user": 1,
        "first_name": "John",
        "last_name": "Doe",
        "num": "****1234",
        "cvv": "***",
        "type": "Visa",
        "expiration_year": 2025,
        "expiration_month": 12,
        "is_active": true,
        "is_preferred": false
    }
}
```

### 📱 **DeviceResource**

За device responses.

```php
// Device response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_user": 1,
        "uuid": "device-uuid-123",
        "os": "iOS",
        "push_token": "push-token-123",
        "access_token": "access-token-123",
        "params": {...},
        "push_turn_on": true,
        "device_model": "iPhone 15",
        "os_version": "17.0"
    }
}
```

### 🌟 **InfluencerResource**

За influencer responses.

```php
// Influencer response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_subscription": 1,
        "title": "Tech News",
        "alias": "tech-news",
        "description": "Latest tech updates",
        "image": "image-url",
        "subscription": {...},
        "feed_list": [...],
        "followers_count": 1000,
        "posts_count": 50
    }
}
```

### 📰 **NewsResource**

За news responses.

```php
// News response
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Breaking News",
        "slug": "breaking-news",
        "image": "image-url",
        "status": 1,
        "params": {...},
        "category": "technology",
        "priority": 5,
        "tags": ["tech", "news"],
        "author": "John Doe",
        "source": "TechNews",
        "read_time": "5 min read",
        "content": "...",
        "reactions": [...],
        "comments_count": 25,
        "views_count": 1000
    }
}
```

### 🛒 **OrderResource**

За order responses.

```php
// Order response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_user": 1,
        "status": "completed",
        "total_amount": 99.99,
        "is_paid": true,
        "payment_method": "credit_card",
        "payment_details": {...},
        "products": [...],
        "billing_address": {...},
        "shipping_address": {...},
        "transaction_id": "txn_123"
    }
}
```

### 📞 **PhoneResource**

За phone responses.

```php
// Phone response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_user": 1,
        "phone_number": "+1234567890",
        "is_verified": true,
        "verification_code": null,
        "verification_attempts": 0,
        "is_primary": true,
        "carrier": "Verizon",
        "country_code": "US",
        "timezone": "America/New_York"
    }
}
```

### 🔒 **PrivateFeedResource**

За private feed responses.

```php
// Private feed response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_user": 1,
        "name": "My Private Feed",
        "description": "Personal updates",
        "token": "feed-token-123",
        "image": "image-url",
        "is_active": true,
        "followers_count": 50,
        "posts_count": 25,
        "settings": {...}
    }
}
```

### 🛍️ **ProductResource**

За product responses.

```php
// Product response
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Premium Subscription",
        "description": "Monthly premium access",
        "price": 9.99,
        "currency": "USD",
        "type": "subscription",
        "category": "premium",
        "is_active": true,
        "is_featured": true,
        "images": [...],
        "alerts": [...],
        "apple_product_id": "com.app.premium",
        "subscription_duration": "1 month"
    }
}
```

### 📢 **PublicFeedResource**

За public feed responses.

```php
// Public feed response
{
    "status": "success",
    "data": {
        "id": 1,
        "uuid": "feed-uuid-123",
        "text": "Check out this amazing news!",
        "text_short": "Amazing news!",
        "url": "https://example.com",
        "is_link": true,
        "subscriptions": [...],
        "images": [...],
        "published_at": "2024-01-01T12:00:00Z",
        "author": {...},
        "reactions": [...],
        "comments_count": 10,
        "views_count": 500
    }
}
```

### 📰 **SubscriptionResource**

За subscription responses.

```php
// Subscription response
{
    "status": "success",
    "data": {
        "id": 1,
        "id_subscription_category": 1,
        "name": "Tech News",
        "title": "Latest Technology News",
        "description": "Stay updated with tech",
        "rule": "Daily updates",
        "alert_type": 1,
        "is_active": true,
        "is_hidden": false,
        "percent": 100.0,
        "price": 0.0,
        "bonus_point": 10,
        "is_public": true,
        "is_custom": false,
        "type": 1,
        "show_reactions": true,
        "show_comments": true,
        "category": {...},
        "subscribers_count": 1000,
        "posts_count": 50
    }
}
```

### 📨 **AllSendFormResource**

За broadcast responses.

```php
// Broadcast response
{
    "status": "success",
    "data": {
        "id": 1,
        "uuid": "broadcast-uuid-123",
        "text": "Important announcement!",
        "text_short": "Announcement",
        "url": null,
        "is_link": false,
        "subscriptions": [...],
        "private_feeds": [...],
        "is_schedule": false,
        "schedule": null,
        "timezone": "UTC",
        "images": [...],
        "status": "sent",
        "sent_count": 100,
        "failed_count": 0,
        "author": {...}
    }
}
```

### 👤 **CustomerResource**

За customer profile responses.

```php
// Customer profile response
{
    "status": "success",
    "data": {
        "id": 1,
        "email": "customer@example.com",
        "first_name": "John",
        "last_name": "Doe",
        "phone_profile": "+1234567890",
        "country": "US",
        "state": "CA",
        "zip": "90210",
        "role": 2,
        "status": 1,
        "is_influencer": false,
        "is_valid_email": true,
        "bonus_point": 150,
        "image": "profile-image.jpg",
        "timezone": "America/Los_Angeles",
        "is_receive_subscribe": true,
        "is_receive_list": true,
        "subscriptions": [...],
        "devices": [...],
        "credit_cards": [...]
    }
}
```

### ❌ **ErrorResource**

За error responses.

```php
// Error response
{
    "status": "error",
    "error": {
        "message": "User not found",
        "code": 404,
        "details": "The requested user does not exist",
        "validation_errors": null,
        "trace_id": "trace-123"
    },
    "meta": {
        "timestamp": "2024-01-01T12:00:00Z",
        "version": "1.0"
    }
}
```

### ✅ **SuccessResource**

За generic success responses.

```php
// Success response
{
    "status": "success",
    "data": {
        "message": "Operation completed successfully",
        "result": "success"
    },
    "meta": {
        "timestamp": "2024-01-01T12:00:00Z",
        "version": "1.0"
    }
}
```

### 👥 **UserCollection**

За user collections with pagination.

```php
// User collection response
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "email": "user1@example.com",
            "first_name": "John",
            "last_name": "Doe",
            "role": 2,
            "status": 1,
            "is_influencer": false,
            "is_valid_email": true,
            "bonus_point": 100,
            "image": null,
            "timezone": "UTC"
        },
        // ... more users
    ],
    "pagination": {
        "total": 100,
        "count": 10,
        "per_page": 10,
        "current_page": 1,
        "total_pages": 10
    },
    "meta": {
        "timestamp": "2024-01-01T12:00:00Z",
        "version": "1.0"
    }
}
```

## 🚀 Како да користиш Resources

### 1. **Во контролерите:**

```php
use App\Http\Resources\AuthenticationResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $result = $this->loginAction->execute($request->all());
            return response()->json(new AuthenticationResource($result));
        } catch (\Exception $e) {
            return response()->json(new ErrorResource([
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid()
            ]), 500);
        }
    }

    public function test(): JsonResponse
    {
        return response()->json(new SuccessResource([
            'message' => 'Authentication module is working!',
            'time' => now()->format('Y-m-d H:i:s')
        ]));
    }
}
```

### 2. **За collections:**

```php
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::paginate(10);
        return response()->json(new UserCollection($users));
    }
}
```

### 3. **За single resources:**

```php
use App\Http\Resources\NewsResource;

class NewsController extends Controller
{
    public function show($id): JsonResponse
    {
        $news = News::findOrFail($id);
        return response()->json(new NewsResource($news));
    }
}
```

## 📋 Response Format Стандарди

### ✅ **Success Response:**

```json
{
    "status": "success",
    "data": { ... },
    "meta": {
        "timestamp": "2024-01-01T12:00:00Z",
        "version": "1.0"
    }
}
```

### ❌ **Error Response:**

```json
{
    "status": "error",
    "error": {
        "message": "Error message",
        "code": 400,
        "details": "Additional details",
        "validation_errors": { ... },
        "trace_id": "unique-trace-id"
    },
    "meta": {
        "timestamp": "2024-01-01T12:00:00Z",
        "version": "1.0"
    }
}
```

### 📄 **Collection Response:**

```json
{
    "status": "success",
    "data": [ ... ],
    "pagination": {
        "total": 100,
        "count": 10,
        "per_page": 10,
        "current_page": 1,
        "total_pages": 10
    },
    "meta": {
        "timestamp": "2024-01-01T12:00:00Z",
        "version": "1.0"
    }
}
```

## 🎯 Предности

1. **Постојаност** - Сите responses имаат ист формат
2. **Консистентност** - Исти полја во сите responses
3. **Верзионирање** - Лесно додавање на нови верзии
4. **Мета податоци** - Timestamp и верзија во секој response
5. **Error handling** - Стандардизирани error responses
6. **Pagination** - Вградена поддршка за pagination
7. **Flexibility** - Лесно прилагодување на различни потреби

## 🔧 Прилагодување

Можеш да ги прилагодиш Resources за твои потреби:

```php
// Додај нови полја
public function toArray(Request $request): array
{
    return [
        'status' => 'success',
        'data' => [
            'id' => $this->resource['id'],
            'custom_field' => $this->resource['custom_field'] ?? 'default',
            // ... други полја
        ]
    ];
}

// Додај нови meta податоци
public function with(Request $request): array
{
    return [
        'meta' => [
            'timestamp' => now()->toISOString(),
            'version' => '1.0',
            'custom_meta' => 'value'
        ],
    ];
}
```

## 📝 Забелешки

-   Сите Resources го следат истиот формат како Yii2 responses
-   Error responses имаат HTTP status codes
-   Success responses имаат `status: "success"`
-   Error responses имаат `status: "error"`
-   Meta податоци се додаваат автоматски
-   Pagination е вградена во Collections
-   Timestamp се генерира автоматски

Ова обезбедува целосна компатибилност со постоечките Yii2 API endpoints! 🎉
