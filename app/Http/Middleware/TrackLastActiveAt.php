<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


/*
 * Credits to Ryan Chandler
 * https://ryangjchandler.co.uk/posts/track-your-users-last-activity-in-laravel
*/

class TrackLastActiveAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if (!$request->user()) {
            return $next($request);
        }

        if ($request->user()->last_active_at || $request->user()->last_active_at->isPast()) {
            $request->user()->update([
                'last_active_at' => now()
            ]);
        }
        return $next($request);
    }
}
