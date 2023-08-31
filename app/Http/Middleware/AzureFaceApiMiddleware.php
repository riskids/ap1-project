<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;

class AzureFaceApiMiddleware
{
    /**
     * Limit seluruh request yang masuk ke route azure api dengan batas 9 request/detik 
     * (batas dari azure 10 request/detik)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'global'; //ganti dengan alamat ip, jika ingin membatasi per ip
        $maxRequests = 9;
        $perSecond = 1;

        RateLimiter::perSecond($key, $maxRequests)->consume($perSecond);

        return $next($request);
    }
}
