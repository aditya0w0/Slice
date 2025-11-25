# Slice - Apple Ecosystem Rental Platform

<p align="center">
  <img src="public/images/logo.svg" width="200" alt="Slice Logo">
</p>

<p align="center">
  <strong>A modern web platform for renting Apple devices with flexible monthly plans</strong>
</p>

---

## Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [User Accounts](#user-accounts)
- [Features Documentation](#features-documentation)
- [Development](#development)
- [Production Deployment](#production-deployment)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Contributing](#contributing)
- [License](#license)

---

## About

**Slice** is a comprehensive rental platform specifically designed for the Apple ecosystem. It enables customers to rent Apple devices (iPhones, iPads, MacBooks, Apple Watches) and purchase Apple services through flexible monthly subscription plans.

### Key Highlights

- **Complete Apple Ecosystem** - Rent latest iPhones, iPads, MacBooks, and accessories
- **Flexible Payment Plans** - Monthly subscriptions with automatic credit scoring
- **KYC Integration** - Built-in Know Your Customer verification system
- **Admin Dashboard** - Comprehensive management interface for devices, orders, and users
- **Real-time Chat** - WebSocket-powered support system with Laravel Reverb
- **Responsive Design** - Beautiful UI built with Tailwind CSS
- **Security First** - HTTPS, HSTS, CSP headers, and session security

---

## Features

### Customer Features

- **Device Catalog** - Browse Apple devices by family (iPhone, iPad, Mac, Watch, Accessories)
- **Smart Search & Filters** - Find devices by specs, price, and availability
- **Rental Cart** - Add multiple devices with rental duration selection
- **Order Management** - Track rental status, payments, and delivery
- **Credit System** - Dynamic credit scoring (500-850) based on payment history
- **KYC Verification** - Upload ID and selfie for account verification
- **Support Chat** - Real-time messaging with admin support
- **User Dashboard** - View orders, credit score, notifications, and profile

### Admin Features

- **Dashboard Overview** - Revenue tracking, order statistics, and analytics
- **Device Management** - CRUD operations for devices with variant support
- **Order Management** - Approve, reject, and track rental orders
- **User Management** - View users, credit scores, KYC status, blacklist control
- **KYC Approval System** - Review and approve/reject verification requests
- **Notification System** - Send targeted notifications to users
- **Support Chat** - Respond to customer inquiries in real-time
- **Profile Management** - Upload profile picture and manage admin settings

### Technical Features

- **Credit Scoring Algorithm** - Automated calculation based on 5 factors (verification, payment history, account age, rental completion, profile completeness)
- **Session Security** - Device fingerprinting, IP tracking, concurrent session limits
- **Real-time Updates** - Laravel Reverb for WebSocket connections
- **Notification Broadcasting** - Real-time notifications via Pusher/Reverb
- **Security Headers** - HSTS, X-Frame-Options, CSP, XSS Protection
- **Responsive Design** - Mobile-first approach with Tailwind CSS

---

## 🛠️ Tech Stack

### Backend

- **Laravel 11.x** - PHP framework
- **PHP 8.2+** - Programming language
- **MySQL** - Database
- **Laravel Reverb** - WebSocket server for real-time features
- **Laravel Broadcasting** - Event broadcasting system

### Frontend

- **Vite** - Build tool and dev server
- **Tailwind CSS 4.x** - Utility-first CSS framework
- **Alpine.js** - Minimal JavaScript framework
- **React 18** - For dashboard and chat components
- **Blade Templates** - Laravel's templating engine

### Development Tools

- **Composer** - PHP dependency manager
- **NPM** - Node package manager
- **ngrok** - Tunneling for external access

---

## System Requirements

- **PHP**: 8.2 or higher
- **Composer**: 2.x
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher
- **MySQL**: 8.0 or higher
- **Web Server**: Apache/Nginx (for production)
- **ngrok**: Latest version (for external access)

---

## Installation

### Step 1: Clone Repository

```bash
git clone https://github.com/aditya0w0/Slice.git
cd Slice
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Configuration

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=slice
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Run Migrations

```bash
# Create database tables
php artisan migrate

# Link storage for file uploads
php artisan storage:link
```

### Step 6: Build Assets

```bash
# Development build with hot reload
npm run dev

# Production build
npm run build
```

### Step 7: Start Development Server

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Reverb WebSocket server
php artisan reverb:start

# Terminal 3 (optional): Vite dev server
npm run dev
```

Your application will be available at `http://localhost:8000`

---

## Configuration

### Reverb Configuration (WebSockets)

For local development:

```env
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
```

For production with ngrok:

```env
REVERB_HOST="your-domain.ngrok-free.dev"
REVERB_PORT=443
REVERB_SCHEME=https
```

### Broadcasting Configuration

```env
BROADCAST_CONNECTION=reverb
```

### Session Configuration

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

### Cache Configuration

```env
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

## User Accounts

### Default Admin Account

After installation, create an admin account:

```bash
php artisan tinker
```

```php
$admin = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@slice.com',
    'password' => bcrypt('your_secure_password'),
    'is_admin' => true,
    'kyc_verified' => true,
    'credit_score' => 850
]);
```

**Default credentials for demo:**

- Email: `siimutngawi69@gmail.com`
- Password: `123jual123`

### Test User Account

**Verified User:**

- Email: `admin@gmail.com`
- Password: `123jual123`
- Credit Score: 850 (Excellent)
- KYC: Verified

---

## Features Documentation

### Credit Scoring System

Credit scores range from 0 to 850 and are calculated based on:

1. **KYC Verification** (+100 points)
    - ID verification
    - Selfie verification
2. **Payment History** (max +500 points)
    - On-time payments: +50 per order
    - Late payments: -25 per order
    - Payment failures: -50 per order
3. **Account Age** (max +100 points)
    - 1+ months: +25
    - 3+ months: +50
    - 6+ months: +75
    - 12+ months: +100
4. **Rental Completion Rate** (max +110 points)
    - Based on completed vs total orders
5. **Profile Completeness** (max +40 points)
    - Phone: +10
    - Address: +10
    - Legal name: +10
    - Date of birth: +10

### Credit Tiers

- **Excellent** (750-850): Full access, premium rates
- **Good** (650-749): Standard access
- **Fair** (550-649): Limited device selection
- **Poor** (<550): Restricted access, higher deposits

### Device Management

Devices support variants (storage, color, etc.) with individual pricing:

```php
// Example device structure
[
    'name' => 'iPhone 15 Pro',
    'family' => 'iphone',
    'base_price' => 50000,
    'variants' => [
        ['storage' => '256GB', 'color' => 'Natural Titanium', 'price' => 50000],
        ['storage' => '512GB', 'color' => 'Blue Titanium', 'price' => 55000]
    ]
]
```

### Order Workflow

1. **Customer** adds devices to cart
2. **Customer** submits order
3. **Admin** reviews and approves/rejects
4. **Admin** updates delivery status
5. **System** updates credit score based on payment
6. **Customer** returns device or extends rental

### Notification System

Admins can send notifications to:

- All users
- Specific user
- Users by credit tier
- Verified users only

---

## Development

### Project Structure

```
slice/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── AuthController.php
│   │   │   ├── CartController.php
│   │   │   ├── DeviceController.php
│   │   │   └── ...
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php
│   │   │   └── SecurityHeaders.php
│   │   └── Requests/          # Form validation
│   ├── Models/
│   │   ├── User.php
│   │   ├── Device.php
│   │   ├── Order.php
│   │   ├── CartItem.php
│   │   └── ...
│   ├── Events/               # Broadcasting events
│   ├── Listeners/
│   └── Services/
├── database/
│   ├── migrations/           # Database schema
│   └── seeders/
├── resources/
│   ├── css/
│   │   └── app.css          # Tailwind imports
│   ├── js/
│   │   ├── app.js
│   │   ├── bootstrap.js     # Laravel Echo setup
│   │   ├── dashboard.jsx    # React dashboard
│   │   └── components/
│   └── views/
│       ├── admin/           # Admin blade templates
│       ├── auth/            # Authentication views
│       ├── devices/         # Device catalog views
│       ├── orders/          # Order views
│       └── partials/        # Reusable components
├── routes/
│   ├── web.php             # Web routes
│   ├── api.php             # API routes
│   └── channels.php        # Broadcasting channels
├── public/
│   ├── build/              # Compiled assets
│   ├── images/
│   │   └── logo.svg
│   └── storage/            # Public file uploads
├── storage/
│   └── app/
│       └── public/
│           └── profile-photos/  # User avatars
├── tests/
├── .env                    # Environment config
├── composer.json
├── package.json
├── vite.config.js
└── tailwind.config.js
```

### Key Files

- **`routes/web.php`** - All route definitions
- **`app/Http/Middleware/AdminMiddleware.php`** - Admin access control
- **`app/Http/Middleware/SecurityHeaders.php`** - Security headers
- **`app/Models/User.php`** - User model with credit scoring
- **`resources/js/bootstrap.js`** - Laravel Echo configuration
- **`vite.config.js`** - Vite build configuration

### Development Commands

```bash
# Clear all caches
php artisan optimize:clear

# Cache config for performance
php artisan config:cache

# Run database migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName -m

# Build assets
npm run build

# Watch for asset changes
npm run dev

# Run tests
php artisan test
```

---

## Production Deployment

### Using ngrok for External Access

1. **Start Laravel server:**

```bash
php artisan serve
```

2. **Start ngrok tunnel:**

```bash
./ngrok.exe http 8000
```

3. **Update `.env` with ngrok URL:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-url.ngrok-free.dev
ASSET_URL=https://your-url.ngrok-free.dev

REVERB_HOST="your-url.ngrok-free.dev"
REVERB_PORT=443
REVERB_SCHEME=https

VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

4. **Build production assets:**

```bash
npm run build
php artisan config:cache
```

5. **Start Reverb server:**

```bash
php artisan reverb:start
```

### Security Checklist

- Set `APP_DEBUG=false`
- Use HTTPS (ngrok provides this)
- Enable HSTS headers
- Configure CSP if needed
- Set strong `APP_KEY`
- Secure database credentials
- Enable session encryption
- Configure CORS properly
- Regular security updates

### Performance Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Build minified assets
npm run build
```

---

## API Documentation

### Authentication Endpoints

```
POST   /register            - Create new user account
POST   /login               - Login user
POST   /logout              - Logout user
```

### Device Endpoints

```
GET    /devices             - List all devices
GET    /devices/{id}        - Get device details
GET    /devices/family/{family} - Get devices by family
```

### Order Endpoints

```
GET    /orders              - List user orders
POST   /orders              - Create new order
GET    /orders/{id}         - Get order details
```

### Cart Endpoints

```
GET    /cart                - View cart
POST   /cart/add            - Add item to cart
DELETE /cart/remove/{id}    - Remove item from cart
POST   /checkout            - Submit order
```

### Admin Endpoints (Authenticated)

```
GET    /admin/dashboard     - Admin dashboard
GET    /admin/devices       - Manage devices
GET    /admin/orders        - Manage orders
GET    /admin/users         - Manage users
GET    /admin/kyc           - KYC approvals
GET    /admin/notifications - Send notifications
GET    /admin/chat          - Support chat
GET    /admin/profile       - Admin profile
PUT    /admin/profile/photo - Update profile picture
```

### Notification Endpoints

```
GET    /notifications       - User notifications (JSON)
POST   /notifications/{id}/read - Mark as read
```

---

## Additional Documentation

- **[CHANGELOG.md](CHANGELOG.md)** - Complete project history with verification steps
- **[SECURITY.md](SECURITY.md)** - Security middleware configuration and hardening guide
- **[SESSION_SECURITY.md](SESSION_SECURITY.md)** - Session management documentation
- **[RISK_ALGORITHM.md](RISK_ALGORITHM.md)** - Credit scoring algorithm details
- **[ALGORITHM_QUICK_REF.md](ALGORITHM_QUICK_REF.md)** - Quick algorithm reference

---

## Contributing

Contributions are welcome. Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Code Style

- Follow PSR-12 for PHP code
- Use Prettier for JavaScript/React
- Follow Laravel best practices
- Write descriptive commit messages

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Author

**Aditya Galang Tri Cahaya**

- GitHub: [@aditya0w0](https://github.com/aditya0w0)

---

## Acknowledgments

- Laravel framework
- Tailwind CSS
- Alpine.js
- React
- Laravel Reverb
- All contributors

---

## Support

For support, email support@slice.com or create an issue in the repository.

---

<p align="center">Copyright (c) 2025 Slice Team</p>
