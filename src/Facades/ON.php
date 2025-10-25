<?php

namespace OnMagik\LaravelClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array validate()
 * @method static array getSiteInfo()
 * @method static array getPackageStatus(string $packageSlug)
 * @method static array health()
 * @method static array calculateEstimate(array $data)
 * @method static array getAvailableVolumes(?int $siteId = null)
 * @method static array get(string $endpoint, array $query = [])
 * @method static array post(string $endpoint, array $data = [])
 * @method static array put(string $endpoint, array $data = [])
 * @method static array delete(string $endpoint)
 * @method static \OnMagik\LaravelClient\Services\ONClient setBearerToken(string $token)
 * @method static mixed cached(string $cacheKey, int $ttl, callable $callback)
 * @method static void clearCache(string $pattern = 'on:*')
 *
 * @see \OnMagik\LaravelClient\Services\ONClient
 */
class ON extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'on';
    }
}