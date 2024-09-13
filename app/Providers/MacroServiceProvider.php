<?php

namespace App\Providers;

use App\Enums\ToastType;
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
        RedirectResponse::macro('withSuccess',
            fn (string $message) => toast(ToastType::SUCCESS, $message, $this)
        );

        RedirectResponse::macro('withError',
            fn (string $message) => toast(ToastType::ERROR, $message, $this)
        );
    }
}
