# ON Magik Laravel Client ✨

Official Laravel package for integrating with ON Platform - **Powered by Magik**.

[![Latest Version](https://img.shields.io/packagist/v/onmagik/laravel-client.svg)](https://packagist.org/packages/onmagik/laravel-client)
[![License](https://img.shields.io/packagist/l/onmagik/laravel-client.svg)](LICENSE)

## Features

- ✨ Magik-powered integration with ON Platform
- 🚀 Easy setup and configuration
- 🔐 Secure API authentication
- 📦 Support for all ON packages (Move, Blog, Analytics, Pay)
- 💾 Built-in caching
- 🎯 Simple facade interface
- 📝 Comprehensive logging

## Installation
```bash
composer require onmagik/laravel-client
```

## Configuration

Publish the config file:
```bash
php artisan vendor:publish --tag=on-config
```

Add to your `.env`:
```env
ON_API_URL=https://on.test/api
ON_API_KEY=your_api_key_here
ON_SITE_ID=1
ON_VERIFY_SSL=false
```

## Usage

### Using Facade
```php
use OnMagik\LaravelClient\Facades\ON;

// Health check
$health = ON::health();

// Get site info
$siteInfo = ON::getSiteInfo();

// Calculate estimate (ON Move) ✨
$estimate = ON::calculateEstimate([
    'volume' => 12,
    'distance_km' => 50,
    'weight_kg' => 600,
]);
```

### Using Dependency Injection
```php
use OnMagik\LaravelClient\Services\ONClient;

class MovingController extends Controller
{
    public function __construct(
        protected ONClient $on
    ) {}
    
    public function calculate(Request $request)
    {
        $result = $this->on->calculateEstimate([
            'volume' => $request->volume,
            'distance_km' => $request->distance_km,
            'weight_kg' => $request->weight_kg,
        ]);
        
        return response()->json($result);
    }
}
```

## Credits

- [ON Magik Team](https://onmagik.ao) ✨

## License

The MIT License (MIT). See [License File](LICENSE) for more information.