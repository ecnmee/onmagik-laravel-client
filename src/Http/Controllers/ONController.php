<?php

namespace ON\LaravelClient\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ON\LaravelClient\Services\ONClient;

class ONController extends Controller
{
    public function __construct(
        protected ONClient $on
    ) {}

    /**
     * Health check
     */
    public function health(): JsonResponse
    {
        $response = $this->on->health();
        return response()->json($response);
    }

    /**
     * Get site information
     */
    public function siteInfo(): JsonResponse
    {
        $response = $this->on->getSiteInfo();
        return response()->json($response);
    }

    /**
     * Check package status
     */
    public function packageStatus(string $package): JsonResponse
    {
        $response = $this->on->getPackageStatus($package);
        return response()->json($response);
    }
}