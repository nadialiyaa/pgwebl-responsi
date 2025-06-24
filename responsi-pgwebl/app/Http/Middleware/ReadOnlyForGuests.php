<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ReadOnlyForGuests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya izinkan method GET untuk guest (tidak login)
        if (!Auth::check() && !in_array($request->method(), ['GET', 'HEAD'])) {
            abort(403, 'Anda harus login untuk menambah atau mengubah data.');
        }

        return $next($request);
    }
    }
