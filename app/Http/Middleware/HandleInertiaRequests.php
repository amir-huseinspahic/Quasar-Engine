<?php

namespace App\Http\Middleware;

use App\Models\AppSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'app' => [
                'settings' => AppSettings::first(),
            ],
            'auth' => [
                'user' => $request->user() ? $request->user() : null,
                'role' => $request->user() ? $request->user()->roles()->first()->name : null,
                'permissions' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name')->toArray() : [],
            ],
            'flash' => fn () => [
                'toasts' => $request->session()->get('toasts'),
                'flagged' => $request->session()->get('flagged') ? $request->session()->get('flagged') : null,
            ],
            'guestLocale' => session()->get('locale') ?? app()->getLocale(),
        ];
    }
}
