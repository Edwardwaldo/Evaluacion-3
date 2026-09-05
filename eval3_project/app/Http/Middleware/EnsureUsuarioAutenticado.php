<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de autorizacion (Eval. 2).
 * Verifica que exista un usuario_id en sesion (es decir, que el
 * usuario haya iniciado sesion mediante AuthController::login)
 * antes de permitir el acceso al modulo de proyectos.
 */
class EnsureUsuarioAutenticado
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('usuario_id')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar.');
        }

        return $next($request);
    }
}
