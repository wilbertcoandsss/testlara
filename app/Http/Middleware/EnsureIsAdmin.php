<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // admin atau bukan
        if (!Auth::check() || !Auth::user()->isAdmin()){
            return redirect()->route('posts.index')->with('error', 'Hanya admin yang boleh masuk!');
        }
        return $next($request);
    }
}
