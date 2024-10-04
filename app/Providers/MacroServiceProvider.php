<?php

namespace App\Providers;

use App\Enums\ToastType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        Carbon::macro('greet', function (string $timezone = 'UTC') {
            $hour = Carbon::now($timezone)->hour;
            if ($hour < 12) {
                return __('app.morning');
            }
            if ($hour < 17) {
                return __('app.afternoon');
            }
            return __('app.evening');
        });

        RedirectResponse::macro('withSuccess',
            fn (string $message) => toast(ToastType::SUCCESS, $message, $this)
        );

        RedirectResponse::macro('withError',
            fn (string $message) => toast(ToastType::ERROR, $message, $this)
        );
    }
}
