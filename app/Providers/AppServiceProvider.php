<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('usage', function (Request $request) {
            if ($request->user()?->isAdmin()) {
                return Limit::none();
            }

            $key = $request->user()?->getAuthIdentifier() ?? $request->ip();

            return Limit::perSecond(10, 15)
                ->by($key)
                ->response(function (Request $request, array $headers) {
                    $payload = [
                        'message' => 'Terlalu banyak permintaan. Coba lagi dalam 15 detik.',
                    ];

                    if ($request->expectsJson()) {
                        return response()->json($payload, 429, $headers);
                    }

                    return response($payload['message'], 429, $headers);
                });
        });
    }
}
