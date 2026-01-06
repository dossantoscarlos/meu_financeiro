<?php

declare(strict_types=1);

namespace App\Metrics;

use App\Models\User;
use Spatie\Prometheus\Facades\Prometheus;

class UserMetrics
{
    public static function register(): void
    {
        Prometheus::addGauge('user_count', fn () => User::count(), null, null, 'Number of users');
    }
}
