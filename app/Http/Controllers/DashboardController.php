<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller {

    public function index() {
        $user = Auth::user();

        $greeting = \Carbon\Carbon::greet($user->settings->timezone);
        $lastActiveAt = $user->last_active_at;
        $lastLoginAt = $user->last_login_at;

        $posts = Post::select('id', 'created_at')
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m');
            });

        $postData = [];
        foreach ($posts as $key => $value) {
            $postData[(int)$key] = count($value);
        }

        for ($i = 1; $i <= 12; $i++) {
            if (empty($postData[$i])) $postData[$i] = 0;

        }

        return Inertia::render('AdminPanel/Dashboard', [
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'greeting' => $greeting,
            'postData' => $postData,
            'lastActiveAt' => $lastActiveAt,
            'lastLoginAt' => $lastLoginAt,
        ]);
    }


    public function settings() {

    }
}
