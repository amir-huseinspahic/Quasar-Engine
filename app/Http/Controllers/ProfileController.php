<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\UserPreferences;
use App\Models\UserSettings;
use DateTimeZone;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show() {
        $user = Auth::user();
        $tzlist = DateTimeZone::listIdentifiers();

        return Inertia::render('AdminPanel/Profile/Show', [
            'user' => $user,
            'timezone_list' => $tzlist,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response {
//        return Inertia::render('Profile/Edit', [
//            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
//            'status' => session('status'),
//        ]);

        $user = Auth::user();
        $tzlist = DateTimeZone::listIdentifiers();

        return Inertia::render('AdminPanel/Profile/Edit', [
            'user' => $user,
            'timezone_list' => $tzlist,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request) {
//        $request->user()->fill($request->validated());
//
//        if ($request->user()->isDirty('email')) {
//            $request->user()->email_verified_at = null;
//        }
//
//        $request->user()->save();
//
//        return Redirect::route('profile.edit');

        $validated = $request->validated();

        try {
            $user = Auth::user();
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
            $user->settings()->update([
                'locale' => $validated['locale'],
                'timezone' => $validated['timezone'],
                'date_format' => $validated['date_format'],
                'time_format' => $validated['time_format'],
                'show_private_posts' => $validated['show_private_posts'],
            ]);
        }
        catch (Exception $e) {
            return Redirect::back()->withError(__('toast.profile_update_failed', ['error' => $e->getMessage()]));
        }

        $response = redirect()->back()->withSuccess(__('toast.profile_update_successful'));
        return Inertia::location($response);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePreferences(Request $request): RedirectResponse {
        $request->validate([
            'page_layout' => ['nullable', 'string'],
            'items_per_page' => ['nullable', 'integer'],
        ]);

        Auth::user()->settings()->update([
            'page_layout' => $request->input('page_layout'),
            'items_per_page' => $request->input('items_per_page'),
        ]);

        return Redirect::back();
    }
}
