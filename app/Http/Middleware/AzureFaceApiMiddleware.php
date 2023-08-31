<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class AzureFaceApiMiddleware
{
    /**
     * Limit seluruh request yang masuk ke route azure api dengan batas 9 request/detik 
     * (batas dari azure 10 request/detik)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'custom_rate_limiter:' . md5($request->ip());
        $maxRequests = 9;
        $interval = 1; // Detik

        $currentTimestamp = microtime(true);
        $requests = Cache::get($key, []);

        // Hapus request yang lebih lama dari interval
        $requests = array_filter($requests, function ($timestamp) use ($currentTimestamp, $interval) {
            return $currentTimestamp - $timestamp <= $interval;
        });

        // Jika jumlah request dalam interval lebih dari batasan, beri delay
        if (count($requests) >= $maxRequests) {
            usleep(($interval * 1000000) - ($currentTimestamp - end($requests)) * 1000000);
        }

        $requests[] = $currentTimestamp;
        Cache::put($key, $requests, $interval * 2); // Simpan dalam 2 interval

        return $next($request);
    }
}
