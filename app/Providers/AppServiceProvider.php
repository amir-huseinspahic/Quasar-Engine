<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

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
    public function boot(): void {
        Paginator::useTailwind();

        Inertia::share([
            'urlPrev' => function () {
                if (url()->previous() !== route('login') &&
                    url()->previous() !== '' &&
                    url()->previous() !== url()->current()) {
                    return url()->previous();
                }
                else return 'empty';
            }
        ]);

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('root')) {
                return true;
            }
        });
    }
}
