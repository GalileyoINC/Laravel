# 🔒 Laravel Request Validation Classes Documentation

Оваа документација објаснува како да користиш Laravel Request класи за валидација на влезни податоци во Galileyo апликацијата.

## 📋 Достапни Request класи

### 🔐 **Authentication/LoginRequest**
За валидација на login податоци.

```php
// Правила за валидација
[
    'email' => ['required', 'email', 'max:255'],
    'password' => ['required', 'string', 'min:6', 'max:255'],
    'device_uuid' => ['nullable', 'string', 'max:255'],
    'device_os' => ['nullable', 'string', 'max:50'],
    'device_model' => ['nullable', 'string', 'max:100'],
    'push_token' => ['nullable', 'string', 'max:500'],
]

// Пример за користење
POST /api/v1/auth/login
{
    "email": "user@example.com",
    "password": "password123",
    "device_uuid": "device-uuid-123",
    "device_os": "iOS",
    "device_model": "iPhone 15",
    "push_token": "push-token-123"
}
```

### 💬 **Chat/ChatListRequest**
За валидација на chat list параметри.

```php
// Правила за валидација
[
    'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
    'offset' => ['nullable', 'integer', 'min:0'],
    'type' => ['nullable', 'string', 'in:private,group,all'],
    'status' => ['nullable', 'string', 'in:active,archived,all'],
]

// Пример за користење
POST /api/v1/chat/list
{
    "limit": 20,
    "offset": 0,
    "type": "private",
    "status": "active"
}
```

### 💬 **Chat/ChatMessagesRequest**
За валидација на chat messages параметри.

```php
// Правила за валидација
[
    'id_conversation' => ['required', 'integer', 'min:1'],
    'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
    'offset' => ['nullable', 'integer', 'min:0'],
    'last_message_id' => ['nullable', 'integer', 'min:1'],
]

// Пример за користење
POST /api/v1/chat/chat-messages
{
    "id_conversation": 1,
    "limit": 50,
    "offset": 0,
    "last_message_id": 100
}
```

### 💬 **Comment/CommentListRequest**
За валидација на comment list параметри.

```php
// Правила за валидација
[
    'id_sms_pool' => ['required', 'integer', 'min:1'],
    'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
    'offset' => ['nullable', 'integer', 'min:0'],
    'id_parent' => ['nullable', 'integer', 'min:1'],
]

// Пример за користење
POST /api/v1/comment/list
{
    "id_sms_pool": 1,
    "limit": 20,
    "offset": 0,
    "id_parent": null
}
```

### 💬 **Comment/CommentCreateRequest**
За валидација на comment creation.

```php
// Правила за валидација
[
    'id_sms_pool' => ['required', 'integer', 'min:1'],
    'message' => ['required', 'string', 'min:1', 'max:1000'],
    'id_parent' => ['nullable', 'integer', 'min:1'],
]

// Пример за користење
POST /api/v1/comment/create
{
    "id_sms_pool": 1,
    "message": "Great post!",
    "id_parent": null
}
```

### 💳 **CreditCard/CreditCardCreateRequest**
За валидација на credit card creation.

```php
// Правила за валидација
[
    'first_name' => ['required', 'string', 'max:255'],
    'last_name' => ['required', 'string', 'max:255'],
    'num' => ['required', 'string', 'regex:/^[0-9]{13,19}$/'],
    'cvv' => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
    'type' => ['required', 'string', 'in:Visa,MasterCard,American Express,Discover'],
    'expiration_year' => ['required', 'integer', 'min:' . date('Y'), 'max:' . (date('Y') + 10)],
    'expiration_month' => ['required', 'integer', 'min:1', 'max:12'],
    'is_preferred' => ['nullable', 'boolean'],
]

// Пример за користење
POST /api/v1/credit-card/create
{
    "first_name": "John",
    "last_name": "Doe",
    "num": "4111111111111111",
    "cvv": "123",
    "type": "Visa",
    "expiration_year": 2025,
    "expiration_month": 12,
    "is_preferred": false
}
```

### 📱 **Device/DeviceUpdateRequest**
За валидација на device update податоци.

```php
// Правила за валидација
[
    'uuid' => ['required', 'string', 'max:255'],
    'os' => ['nullable', 'string', 'max:50'],
    'push_token' => ['nullable', 'string', 'max:500'],
    'params' => ['nullable', 'array'],
    'push_turn_on' => ['nullable', 'boolean'],
    'device_model' => ['nullable', 'string', 'max:100'],
    'os_version' => ['nullable', 'string', 'max:50'],
    'app_version' => ['nullable', 'string', 'max:50'],
    'screen_resolution' => ['nullable', 'string', 'max:50'],
    'timezone' => ['nullable', 'string', 'max:100'],
    'language' => ['nullable', 'string', 'max:10'],
]

// Пример за користење
POST /api/v1/device/update
{
    "uuid": "device-uuid-123",
    "os": "iOS",
    "push_token": "push-token-123",
    "params": {
        "device_model": "iPhone 15",
        "os_version": "17.0",
        "app_version": "1.0.0"
    },
    "push_turn_on": true,
    "timezone": "UTC",
    "language": "en"
}
```

### 📰 **News/NewsListRequest**
За валидација на news list параметри.

```php
// Правила за валидација
[
    'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
    'offset' => ['nullable', 'integer', 'min:0'],
    'category' => ['nullable', 'string', 'max:100'],
    'status' => ['nullable', 'integer', 'in:0,1,2'],
    'search' => ['nullable', 'string', 'max:255'],
    'sort_by' => ['nullable', 'string', 'in:created_at,updated_at,name,priority'],
    'sort_order' => ['nullable', 'string', 'in:asc,desc'],
]

// Пример за користење
POST /api/v1/news/list
{
    "limit": 20,
    "offset": 0,
    "category": "technology",
    "status": 1,
    "search": "AI",
    "sort_by": "created_at",
    "sort_order": "desc"
}
```

### 🛒 **Order/CreateOrderRequest**
За валидација на order creation.

```php
// Правила за валидација
[
    'products' => ['required', 'array', 'min:1'],
    'products.*.id' => ['required', 'integer', 'min:1'],
    'products.*.quantity' => ['required', 'integer', 'min:1'],
    'products.*.price' => ['required', 'numeric', 'min:0'],
    'billing_address' => ['nullable', 'array'],
    'billing_address.first_name' => ['nullable', 'string', 'max:255'],
    'billing_address.last_name' => ['nullable', 'string', 'max:255'],
    'billing_address.email' => ['nullable', 'email', 'max:255'],
    'billing_address.phone' => ['nullable', 'string', 'max:50'],
    'billing_address.address' => ['nullable', 'string', 'max:500'],
    'billing_address.city' => ['nullable', 'string', 'max:100'],
    'billing_address.state' => ['nullable', 'string', 'max:100'],
    'billing_address.zip' => ['nullable', 'string', 'max:20'],
    'billing_address.country' => ['nullable', 'string', 'max:100'],
    'shipping_address' => ['nullable', 'array'],
    'payment_method' => ['nullable', 'string', 'in:credit_card,apple_pay,google_pay'],
    'notes' => ['nullable', 'string', 'max:1000'],
]

// Пример за користење
POST /api/v1/order/create
{
    "products": [
        {
            "id": 1,
            "quantity": 2,
            "price": 99.99
        }
    ],
    "billing_address": {
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "address": "123 Main St",
        "city": "New York",
        "state": "NY",
        "zip": "10001",
        "country": "USA"
    },
    "shipping_address": {
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "address": "123 Main St",
        "city": "New York",
        "state": "NY",
        "zip": "10001",
        "country": "USA"
    },
    "payment_method": "credit_card",
    "notes": "Please deliver during business hours"
}
```

### 📞 **Phone/PhoneVerifyRequest**
За валидација на phone verification.

```php
// Правила за валидација
[
    'phone_number' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/'],
    'verification_code' => ['required', 'string', 'regex:/^[0-9]{4,6}$/'],
    'country_code' => ['nullable', 'string', 'max:10'],
]

// Пример за користење
POST /api/v1/phone/verify
{
    "phone_number": "+1234567890",
    "verification_code": "123456",
    "country_code": "US"
}
```

### 📰 **Subscription/SubscriptionRequest**
За валидација на subscription management.

```php
// Правила за валидација
[
    'id' => ['required', 'integer', 'min:1'],
    'checked' => ['required', 'boolean'],
    'zip' => ['nullable', 'string', 'max:20'],
    'sub_type' => ['nullable', 'string', 'in:regular,premium,vip'],
]

// Пример за користење
POST /api/v1/feed/set
{
    "id": 1,
    "checked": true,
    "zip": "10001",
    "sub_type": "regular"
}
```

### 👤 **Customer/CustomerProfileRequest**
За валидација на customer profile updates.

```php
// Правила за валидација
[
    'first_name' => ['nullable', 'string', 'max:255'],
    'last_name' => ['nullable', 'string', 'max:255'],
    'phone_profile' => ['nullable', 'string', 'max:50'],
    'country' => ['nullable', 'string', 'max:100'],
    'state' => ['nullable', 'string', 'max:100'],
    'zip' => ['nullable', 'string', 'max:20'],
    'timezone' => ['nullable', 'string', 'max:100'],
    'image' => ['nullable', 'string', 'max:500'],
    'is_receive_subscribe' => ['nullable', 'boolean'],
    'is_receive_list' => ['nullable', 'boolean'],
]

// Пример за користење
POST /api/v1/customer/update-profile
{
    "first_name": "John",
    "last_name": "Doe",
    "phone_profile": "+1234567890",
    "country": "USA",
    "state": "NY",
    "zip": "10001",
    "timezone": "America/New_York",
    "image": "profile-image.jpg",
    "is_receive_subscribe": true,
    "is_receive_list": true
}
```

## 🚀 Како да користиш Request класи

### 1. **Во контролерите:**

```php
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\AuthenticationResource;
use App\Http\Resources\ErrorResource;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Request validation is handled automatically by LoginRequest
            $result = $this->loginAction->execute($request->validated());
            
            return response()->json(new AuthenticationResource($result));
            
        } catch (\Exception $e) {
            return response()->json(new ErrorResource([
                'message' => $e->getMessage(),
                'code' => 500,
                'trace_id' => uniqid()
            ]), 500);
        }
    }
}
```

### 2. **Автоматска валидација:**

```php
// Laravel автоматски ги валидира податоците
// Ако валидацијата не успее, се враќа 422 Unprocessable Entity
// со детални error messages

// Пример за неуспешна валидација:
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["Email is required"],
        "password": ["Password must be at least 6 characters"]
    }
}
```

### 3. **Пристап до валидирани податоци:**

```php
// $request->validated() - само валидирани податоци
$validatedData = $request->validated();

// $request->all() - сите податоци (не се препорачува)
$allData = $request->all();

// $request->only(['field1', 'field2']) - само одредени полја
$specificData = $request->only(['email', 'password']);
```

### 4. **Custom валидација:**

```php
// Во Request класата можеш да додадеш custom валидација
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        if ($this->input('password') !== $this->input('password_confirmation')) {
            $validator->errors()->add('password', 'Passwords do not match.');
        }
    });
}
```

## 📋 Валидациски правила

### **Основни правила:**
- `required` - задолжително поле
- `nullable` - опционално поле
- `string` - текст
- `integer` - број
- `numeric` - број (вклучува decimal)
- `boolean` - true/false
- `array` - низа
- `email` - валидна email адреса
- `url` - валидна URL
- `date` - валиден датум
- `json` - валиден JSON

### **Должина правила:**
- `min:value` - минимална должина/вредност
- `max:value` - максимална должина/вредност
- `between:min,max` - помеѓу две вредности

### **Формат правила:**
- `regex:pattern` - regex pattern
- `in:value1,value2` - една од дозволените вредности
- `not_in:value1,value2` - не една од забранетите вредности

### **Фајл правила:**
- `file` - фајл
- `image` - слика
- `mimes:jpeg,png` - дозволени MIME типови
- `max:size` - максимална големина

## 🎯 Предности

1. **Централизирана валидација** - сите правила на едно место
2. **Автоматска валидација** - Laravel ги валидира автоматски
3. **Детални error messages** - прилагодени пораки за грешки
4. **Reusability** - можеш да ги користиш во повеќе контролери
5. **Type safety** - `$request->validated()` враќа само валидни податоци
6. **Custom rules** - можеш да додадеш custom валидација
7. **Consistent errors** - сите валидациски грешки имаат ист формат

## 🔧 Прилагодување

Можеш да ги прилагодиш Request класите за твои потреби:

```php
// Додај нови правила
public function rules(): array
{
    return [
        'email' => ['required', 'email', 'max:255'],
        'custom_field' => ['nullable', 'string', 'max:100'],
        // ... други правила
    ];
}

// Додај custom пораки
public function messages(): array
{
    return [
        'email.required' => 'Email is required',
        'custom_field.max' => 'Custom field cannot exceed 100 characters',
        // ... други пораки
    ];
}

// Додај custom атрибути
public function attributes(): array
{
    return [
        'email' => 'email address',
        'custom_field' => 'custom field',
        // ... други атрибути
    ];
}
```

## 📝 Забелешки

- Сите Request класи го следат Laravel стандардот
- Валидацијата се случува пред да се изврши controller методот
- Ако валидацијата не успее, се враќа 422 статус код
- `$request->validated()` е побезбедно од `$request->all()`
- Custom правила можеш да додадеш со `withValidator()` метод
- Error messages се прикажуваат на јазикот на апликацијата

Ова обезбедува целосна валидација на влезни податоци и подобрена безбедност! 🔒
