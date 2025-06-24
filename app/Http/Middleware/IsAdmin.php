<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Revisamos si el usuario ha iniciado sesión Y si su rol es 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Si cumple la condición, lo dejamos pasar a la siguiente petición.
            return $next($request);
        }

        // Si no es admin, lo redirigimos a su panel de control con un mensaje de error.
        return redirect('dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
    }
}