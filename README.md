# SwiftCart 🛍️

A full-stack e-commerce application built with Laravel 11, featuring a complete shopping experience and admin panel.

## 🔗 Live Demo
Coming soon

## 🖼️ Screenshots
Coming soon

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 |
| Frontend | Blade + Tailwind CSS |
| Database | MySQL |
| Payments | Stripe |
| Auth | Laravel Breeze |
| Testing | PHPUnit |

## ✨ Features

### Customer
- 🔍 Product listing with search and category filters
- 🛒 Shopping cart (add, update, remove)
- 💳 Stripe checkout with test payments
- 📦 Order history and order detail
- ⭐ Product reviews (verified purchases only)

### Admin Panel
- 📊 Dashboard with revenue and order stats
- 📁 Category management (CRUD)
- 📦 Product management (CRUD) with image uploads
- 🧾 Order management with status updates
- ⭐ Review approval system

## 🚀 Setup Instructions

**1. Clone the repository**
```bash
git clone https://github.com/YOUR_USERNAME/swiftcart.git
cd swiftcart
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Update `.env`**
```env
DB_DATABASE=laravel_shop
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
```

**5. Run migrations and seeders**
```bash
php artisan migrate --seed
```

**6. Start the server**
```bash
php artisan serve
npm run dev
```

**7. Visit `http://localhost:8000`**

## 🔐 Demo Accounts

| Role | Email | Password |
|---|---|---|
| Admin | admin@shop.com | password |
| Customer | customer@shop.com | password |

## 💳 Test Payment

Use Stripe test card:
```
Card Number: 4242 4242 4242 4242
Expiry: Any future date
CVC: Any 3 digits
```

## 🧪 Running Tests

```bash
php artisan test
```

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      ← Admin controllers
│   │   └── Shop/       ← Customer controllers
│   └── Middleware/     ← Custom middleware
├── Models/             ← Eloquent models
└── Services/           ← Business logic
```

## 🎯 Key Technical Decisions

- **Service classes** — business logic separated from controllers
- **Route model binding** — clean controller methods
- **Database transactions** — atomic order creation
- **Eager loading** — N+1 query prevention
- **Custom middleware** — admin route protection
- **Policy-based authorization** — ownership checks