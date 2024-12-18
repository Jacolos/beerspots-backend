<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Limit zgłoszeń na minutę
        RateLimiter::for('reports', function (Request $request) {
            return [
                // Limit na minutę
                Limit::perMinute(2)->by($request->user()?->id ?: $request->ip()),
                
                // Limit dzienny
                Limit::perDay(30)->by($request->user()?->id ?: $request->ip()),
                
                // Limit na godzinę
                Limit::perHour(10)->by($request->user()?->id ?: $request->ip()),
                
                // Limit dla konkretnego miejsca
                Limit::perDay(1)
                    ->by($request->user()?->id . ':spot:' . $request->spot_id)
                    ->response(function () {
                        return response()->json([
                            'message' => 'To miejsce zostało już przez Ciebie zgłoszone. Poczekaj na moderację.',
                        ], 429);
                    }),
            ];
        });
    }
}