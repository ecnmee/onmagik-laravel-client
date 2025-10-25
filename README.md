# ON Laravel Client

Official Laravel package for integrating with ON Platform.

[![Latest Version](https://img.shields.io/packagist/v/on/laravel-client.svg)](https://packagist.org/packages/on/laravel-client)
[![License](https://img.shields.io/packagist/l/on/laravel-client.svg)](LICENSE)

## Features

- 🚀 Easy integration with ON Platform
- 🔐 Secure API authentication
- 📦 Support for all ON packages (Move, Blog, Analytics, Pay)
- 💾 Built-in caching
- 🎯 Simple facade interface
- 📝 Comprehensive logging

## Installation
```bash
composer require on/laravel-client
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

# Optional
ON_BEARER_TOKEN=user_token_here
ON_TIMEOUT=30
ON_CACHE_ENABLED=true
ON_CACHE_TTL=3600

# Packages
ON_PACKAGE_MOVE=true
ON_PACKAGE_BLOG=false
ON_PACKAGE_ANALYTICS=false
ON_PACKAGE_PAY=false
```

## Usage

### Using Facade
```php
use ON\LaravelClient\Facades\ON;

// Health check
$health = ON::health();

// Get site info
$siteInfo = ON::getSiteInfo();

// Calculate estimate (ON Move)
$estimate = ON::calculateEstimate([
    'volume' => 12,
    'distance_km' => 50,
    'weight_kg' => 600,
]);

// Get available volumes
$volumes = ON::getAvailableVolumes();

// Check package status
$status = ON::getPackageStatus('on-move');
```

### Using Dependency Injection
```php
use ON\LaravelClient\Services\ONClient;

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

### With User Authentication
```php
use ON\LaravelClient\Facades\ON;

// Set user token
ON::setBearerToken($user->on_token);

// Now make authenticated requests
$siteInfo = ON::getSiteInfo();
```

### Using Cache
```php
// Cache automatically (based on config)
$volumes = ON::getAvailableVolumes();

// Manual cache control
$result = ON::cached('volumes', 3600, function () {
    return ON::getAvailableVolumes();
});

// Clear cache
ON::clearCache();
```

## API Endpoints

The package automatically registers these routes:

- `GET /api/on/health` - Health check
- `GET /api/on/site-info` - Get site information
- `GET /api/on/package-status/{package}` - Check package status

## Available Methods

### Authentication
```php
ON::validate()                        // Validate credentials
ON::setBearerToken(string $token)    // Set user token
```

### Site Management
```php
ON::getSiteInfo()                     // Get site information
ON::getPackageStatus(string $slug)   // Check package status
ON::health()                          // Health check
```

### ON Move
```php
ON::calculateEstimate(array $data)    // Calculate moving estimate
ON::getAvailableVolumes(?int $id)    // Get available volumes
```

### Generic Requests
```php
ON::get(string $endpoint, array $query)    // GET request
ON::post(string $endpoint, array $data)    // POST request
ON::put(string $endpoint, array $data)     // PUT request
ON::delete(string $endpoint)               // DELETE request
```

## Error Handling
```php
$result = ON::calculateEstimate([...]);

if ($result['success']) {
    // Success
    $data = $result['data'];
} else {
    // Error
    $error = $result['error'];
    $message = $result['message'];
}
```

## Testing
```bash
composer test
```

## Security

If you discover any security issues, please email security@on-platform.ao instead of using the issue tracker.

## Credits

- [ON Platform Team](https://on-platform.ao)

## License

The MIT License (MIT). See [License File](LICENSE) for more information.