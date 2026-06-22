<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class BlockSuspiciousIPs
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;
    protected $blockMinutes = 1;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = $this->throttleKey($ip);

        if (Cache::has($key . ':blocked')) {
            Session::flash("errors", "Your IP has been blocked for $this->blockMinutes minute(s) due to too many requests");
            return redirect()->back();
        }
        // if (Cache::has($key . ':blocked')) {
        //     abort(429, "Hai superato il numero massimo di richieste consentite.");
        // }

        if (!Cache::has($key)) {
            Cache::put($key, 1, $this->decayMinutes * 60);
        }

        if (Cache::has($key)) {
            $attempts = Cache::increment($key);
            if ($attempts > $this->maxAttempts) {
                Cache::put($key . ":blocked", true, $this->blockMinutes * 60);
                Log::warning("IP $ip has been blocked for $this->blockMinutes minute(s) due to too many requests");

                // Log::warning("IP $ip has been blocked for $this->blockMinutes minute(s) due to too many requests"); abort(429, "Hai superato il numero massimo di richieste consentite. Riprova tra qualche minuto.");

                Session::flash("errors", "Your IP has been blocked for $this->blockMinutes minute(s) due to too many requests");
                return redirect()->back();
            }
        } else {
            Cache::put($key, 1, $this->decayMinutes * 60);
        }

        return $next($request);
    }

    protected function throttleKey($ip)
    {
        return "throttle:" . sha1($ip);
    }
}