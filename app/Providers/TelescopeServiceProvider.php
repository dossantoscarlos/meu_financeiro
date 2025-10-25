<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $incomingEntry): true {
            if ($this->app->environment('local')) {
                return true;
            }

            if ($incomingEntry->isReportableException()) {
                return true;
            }

            if ($incomingEntry->isFailedRequest()) {
                return true;
            }

            if ($incomingEntry->isFailedJob()) {
                return true;
            }

            if ($incomingEntry->isScheduledTask()) {
                return true;
            }

            if ($incomingEntry->hasMonitoredTag()) {
                return true;
            }

            if ($incomingEntry->isDump()) {
                return true;
            }

            return (bool) $incomingEntry->type = EntryType::LOG;
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    // protected function gate(): void
    // {
    //     Gate::define('viewTelescope', function ($user) {
    //         return in_array($user->email, [
    //             //
    //         ]);
    //     });
    // }

    protected function gate()
    {
        Gate::define('viewTelescope', fn ($user) => $user->can(config('filament-debugger.permissions.telescope')));
    }

    // protected function authorization()
    // {
    //     Auth::setDefaultDriver(config('filament.auth.guard'));

    //     parent::authorization();
    // }
}
