<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!$request->user()) {
            return response()->json([
                'message' => 'No autenticado'
            ], 401);
        }

        // Verificar si el email está verificado
        if (!$request->user()->email_verified_at) {
            return response()->json([
                'message' => 'Debes verificar tu email para realizar esta acción',
                'requires_verification' => true
            ], 403);
        }

        return $next($request);
    }
}
