<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use DateTimeZone;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravolt\Avatar\Facade as Avatar;
use Spatie\Permission\Models\Role;

class UserController extends Controller {
    public function index(): Response {

        $userSettings = auth()->user()->settings->first();

        $users = User::query()
            ->orderBy('name')
            ->where(function ($query) {
                $query->when(Request::input('search'), function ($q, $search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                    $q->where('is_hidden', '!=', true);
                });
            })
            ->where('is_hidden', '!=', true)
            ->when(Request::input('role'), function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->when(Request::input('fromDate'), function ($query, $fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when(Request::input('toDate'), function ($query, $toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            })
            ->paginate($userSettings->items_per_page)
            ->withQueryString()
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'roles' => $user->roles->pluck('name')->first(),
            ]);

        $roles = Role::all()->whereNotIn('name', ['root'])->pluck('name', 'name')->toArray();

        return Inertia::render('AdminPanel/Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => Request::only(['search', 'roles']),
        ]);
    }

    public function create(): Response {
        $tzlist = DateTimeZone::listIdentifiers();

        $roles = [];
        if (auth()->user()->hasRole('root')) {
            $roles = Role::all()->pluck('name', 'name')->toArray();
        }
        else $roles = Role::all()->whereNotIn('name', ['root'])->pluck('name', 'name')->toArray();

        return Inertia::render('AdminPanel/Users/Create', [
            'timezone_list' => $tzlist,
            'roles' => $roles,
        ]);
    }

    public function store(UserStoreRequest $request): RedirectResponse {
        $validated = $request->validated();

        try {
            if ($validated['role'] === 'root') {
                return Redirect::back()->withErrors([
                    'role' => 'Root accounts cannot be created, please contact your IT department for support.',
                ]);
            }

            if (auth()->user()->roles()->first()->name !== 'root' && $validated['role'] === 'admin') {
                return Redirect::back()->withErrors(['role' => 'Only root can create admin users.']);
            }

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

            Avatar::create($validated['name'])->save(public_path('media/users/avatars/' . $user->id . '.png'));

            $user->assignRole($validated['role']);

            event(new Registered($user));
        }
        catch (Exception $e) {
            Redirect::back()->withError(__('toast.user_create_failed', ['error' => $e->getMessage()]));
        }

        return Redirect::back()->withSuccess(__('toast.user_create_successful', ['user' => $validated['name']]));
    }

    public function show(User $user): Response|RedirectResponse {

        try {
            $user->posts;
            $user->settings;
            $user->roles;

            if ($user->hasRole('root') && !auth()->user()->hasRole('root')) {
                throw new Exception(__('toast.missing_role'));
            }
        }
        catch (Exception $e) {
            return Redirect::back()->withError($e->getMessage());
        }

        return Inertia::render('AdminPanel/Users/Show', [
            'user' => $user
        ]);
    }

    public function edit(User $user): Response | RedirectResponse {
//        $tzlist = DateTimeZone::listIdentifiers();
//
//        $rolesList = [];
//        if (auth()->user()->hasRole('root')) {
//            $rolesList = Role::all()->whereNotIn('name', ['root'])->pluck('name', 'name')->toArray();
//        }
//        else $rolesList = Role::all()->whereNotIn('name', ['root', 'admin'])->pluck('name', 'name')->toArray();
//
//        $user->settings;
//        $user->roles = $user->roles()->pluck('name')->toArray();
//
//        return Inertia::render('AdminPanel/Users/Edit', [
//            'user' => $user,
//            'timezone_list' => $tzlist,
//            'roles_list' => $rolesList
//        ]);

        $tzlist = DateTimeZone::listIdentifiers();
        $rolesList = [];

        if (auth()->user()->hasRole('root')) {
            $rolesList = Role::all()->whereNotIn('name', ['root'])->pluck('name', 'name')->toArray();
        }
        else $rolesList = Role::all()->whereNotIn('name', ['root', 'admin'])->pluck('name', 'name')->toArray();

        try {
            if ($user->hasRole('root') && !auth()->user()->hasRole('root')) {
                throw new Exception(__('toast.missing_role'));
            }

            $user->settings;
            $user->roles = $user->roles()->pluck('name')->toArray();

        }
        catch (Exception $e) {
            return Redirect::route('users.index')->withError($e->getMessage());
        }

        return Inertia::render('AdminPanel/Users/Edit', [
            'user' => $user,
            'timezone_list' => $tzlist,
            'roles_list' => $rolesList
        ]);
    }

    public function update(UserUpdateRequest $request, User $user) {
        $validated = $request->validated();

        try {
            if ($validated['role'] === 'root') {
                return Redirect::back()->withErrors(['role' => 'Root accounts cannot be updated, please contact your IT department for support.']);
            }

            if (auth()->user()->roles()->first()->name !== 'root' && $validated['role'] === 'admin') {
                return Redirect::back()->withErrors(['role' => 'Only root can create admin users.']);
            }

            if ($validated['password']) {
                $user->update([
                    'password' => Hash::make($validated['password'])
                ]);
            }

            $oldName = $user->name;

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

            if($validated['role']) {
                $user->syncRoles($validated['role']);
            }

            if ($validated['name'] !== $oldName) {
                Avatar::create($validated['name'])->save(public_path('media/users/avatars/' . $user->id . '.png'));
            }
        }
        catch (Exception $e) {
            return Redirect::back()->withError(__('toast.user_update_failed', ['error' => $e->getMessage()]));
        }

        $response = redirect()->back()->withSuccess(__('toast.user_update_successful', ['user' => $validated['name']]));
        return Inertia::location($response);
    }

    public function destroy(User $user): RedirectResponse {
        try {
            if ($user->hasRole('root') && !auth()->user()->hasRole('root')) {
                throw new Exception(__('toast.missing_role'));
            }

            $user->settings()->delete();
            $user->delete();
        }
        catch (Exception $e) {
            return Redirect::back()->withError(__('toast.user_destroy_failed', ['error' => $e->getMessage()]));
        }

        return Redirect::route('users.index')->withSuccess(__('toast.user.destroy_successful', ['user' => $user['name']]));
    }
}
