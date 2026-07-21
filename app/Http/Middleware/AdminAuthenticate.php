<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     * Redirect to admin login if not authenticated as admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_logged_in') || !session('admin_user_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai admin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
