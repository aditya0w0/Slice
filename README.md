# Slice - Apple Ecosystem Rent

<p align="center">
  <img src="public/images/logo.svg" width="200" alt="Slice Logo">
</p>

## About Slice

**Slice** is a modern web platform where customers can rent Apple devices and purchase Apple services with flexible monthly plans. Stay connected and up-to-date with the latest Apple ecosystem without the commitment of full ownership.

### Features

- 🍎 **Rent Apple Devices** - Access the latest iPhones, iPads, MacBooks, and more
- 💳 **Flexible Monthly Plans** - Pay-as-you-go with customizable rental periods
- 🔄 **Upgrade Anytime** - Switch to newer models when they're released
- 🛡️ **Apple Services** - Bundle with Apple Care, iCloud, Apple Music, and more
- 📦 **Easy Returns** - Hassle-free return process when you're done

## Tech Stack

- **Laravel 11** - PHP framework for backend
- **Vite** - Modern build tool for frontend assets
- **Tailwind CSS** - Utility-first CSS framework
- **Blade Templates** - Laravel's powerful templating engine

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

### Installation

1. Clone the repository
```bash
git clone https://github.com/aditya0w0/Slice.git
cd Slice
```

2. Install dependencies
```bash
composer install
npm install
```

3. Set up environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env` file

5. Run migrations
```bash
php artisan migrate
```

6. Build assets and start development server
```bash
npm run dev
php artisan serve
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
