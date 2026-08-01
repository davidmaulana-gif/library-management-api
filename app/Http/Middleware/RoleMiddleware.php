<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $peran)
    {
        if ($request->user()->peran_id != $peran) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }


        return $next($request);
    }
}
