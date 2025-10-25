<?php

namespace OnMagik\LaravelClient\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ONClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected ?string $bearerToken = null;
    protected int $timeout = 30;
    protected bool $verifySSL = false;

    public function __construct(string $baseUrl, string $apiKey, ?string $bearerToken = null)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->bearerToken = $bearerToken;
        $this->verifySSL = config('on.verify_ssl', false);
        $this->timeout = config('on.timeout', 30);
    }

    /**
     * Set bearer token for authenticated requests
     */
    public function setBearerToken(string $token): self
    {
        $this->bearerToken = $token;
        return $this;
    }

    /**
     * Validate credentials with ON Platform
     */
    public function validate(): array
    {
        return $this->post('/remote/validate');
    }

    /**
     * Get site information
     */
    public function getSiteInfo(): array
    {
        return $this->get('/remote/site/info');
    }

    /**
     * Check package status
     */
    public function getPackageStatus(string $packageSlug): array
    {
        return $this->get('/remote/packages/status', ['package' => $packageSlug]);
    }

    /**
     * Health check
     */
    public function health(): array
    {
        return $this->get('/health');
    }

    /**
     * Calculate estimate (ON Move)
     */
    public function calculateEstimate(array $data): array
    {
        return $this->post('/estimate', array_merge([
            'site_id' => config('on.site_id'),
        ], $data));
    }

    /**
     * Get available volumes (ON Move)
     */
    public function getAvailableVolumes(?int $siteId = null): array
    {
        $siteId = $siteId ?? config('on.site_id');
        return $this->get("/sites/{$siteId}/volumes");
    }

    /**
     * Generic GET request
     */
    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, [], $query);
    }

    /**
     * Generic POST request
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, $data);
    }

    /**
     * Generic PUT request
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, $data);
    }

    /**
     * Generic DELETE request
     */
    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Generic HTTP request
     */
    protected function request(string $method, string $endpoint, array $data = [], array $query = []): array
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $request = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->accept('application/json');

            if (!$this->verifySSL) {
                $request = $request->withOptions(['verify' => false]);
            }

            if (!empty($query) && $method === 'GET') {
                $request = $request->withQueryParameters($query);
            }

            $response = match($method) {
                'GET' => $request->get($url),
                'POST' => $request->post($url, $data),
                'PUT' => $request->put($url, $data),
                'DELETE' => $request->delete($url),
                default => throw new \InvalidArgumentException("Invalid HTTP method: {$method}"),
            };

            Log::info('ON Platform API Request', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => $response->status(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            $error = $response->json();
            
            Log::error('ON Platform API Error', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'error' => $error,
            ]);

            return [
                'success' => false,
                'message' => $error['message'] ?? 'Error communicating with ON Platform.',
                'error' => $error['error'] ?? 'UNKNOWN_ERROR',
                'status' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('ON Platform API Exception', [
                'method' => $method,
                'endpoint' => $endpoint,
                'exception' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Connection error with ON Platform.',
                'error' => 'CONNECTION_ERROR',
                'exception' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get request headers
     */
    protected function getHeaders(): array
    {
        $headers = [
            'X-Site-API-Key' => $this->apiKey,
            'Accept' => 'application/json',
        ];

        if ($this->bearerToken) {
            $headers['Authorization'] = 'Bearer ' . $this->bearerToken;
        }

        return $headers;
    }

    /**
     * Cache helper
     */
    public function cached(string $cacheKey, int $ttl, callable $callback)
    {
        if (!config('on.cache.enabled', true)) {
            return $callback();
        }

        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Clear cache
     */
    public function clearCache(string $pattern = 'on:*'): void
    {
        Cache::flush();
    }
}