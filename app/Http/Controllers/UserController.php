<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use DateTimeZone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller {
    public function index(): Response
    {
        $userSettings = auth()->user()->settings->first();

        $users = User::query()
            ->orderBy('name')
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate($userSettings->items_per_page)
            ->withQueryString()
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ]);

        return Inertia::render('AdminPanel/Users/Index', [
            'users' => $users,
            'filters' => Request::only(['search', 'category', 'author', 'published']),
        ]);
    }

    public function create(): Response {
        $tzlist = DateTimeZone::listIdentifiers();

        return Inertia::render('AdminPanel/Users/Create', [
            'timezone_list' => $tzlist
        ]);
    }

    public function store(UserStoreRequest $request): RedirectResponse {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->settings()->create([
            'locale' => $validated['locale'],
            'timezone' => $validated['timezone'] ?? 'Europe/London',
            'date_format' => $validated['date_format'] ?? 'YYYY-MM-DD',
            'time_format' => $validated['time_format'] ?? 'HH:mm:ss'
        ]);

        event(new Registered($user));

        return Redirect::route('users.index');
    }

    public function show(User $user): Response {
        $user->posts;
        $user->settings;

        return Inertia::render('AdminPanel/Users/Show', [
            'user' => $user
        ]);
    }

    public function edit(User $user): Response {
        $tzlist = DateTimeZone::listIdentifiers();
        $user->settings;

        return Inertia::render('AdminPanel/Users/Edit', [
            'user' => $user,
            'timezone_list' => $tzlist
        ]);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse {
        $validated = $request->validated();

        if ($validated['password']) {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->settings()->update([
            'locale' => $validated['locale'],
            'timezone' => $validated['timezone'],
            'date_format' => $validated['date_format'],
            'time_format' => $validated['time_format']
        ]);

        return Redirect::route('users.show', $user);
    }

    public function destroy(User $user): RedirectResponse {
        $user->settings()->delete();
        $user->delete();

        return Redirect::route('users.index');
    }
}
