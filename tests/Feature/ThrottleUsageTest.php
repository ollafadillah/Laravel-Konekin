<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ThrottleUsageTest extends TestCase
{
    public function test_usage_limiter_blocks_after_ten_requests_per_minute(): void
    {
        Route::middleware('throttle:usage')->get('/__throttle-usage-test', function () {
            return response()->json(['ok' => true]);
        });

        for ($i = 0; $i < 10; $i++) {
            $response = $this->withServerVariables([
                'REMOTE_ADDR' => '127.0.0.1',
            ])->get('/__throttle-usage-test');

            $response->assertOk();
        }

        $this->withServerVariables([
            'REMOTE_ADDR' => '127.0.0.1',
        ])->get('/__throttle-usage-test')->assertStatus(429);
    }
}
