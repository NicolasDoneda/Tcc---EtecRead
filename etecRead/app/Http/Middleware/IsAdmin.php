<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $user = $request->user(); // pega o usuário autenticado

        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Acesso negado. Apenas admins podem acessar.'], 403);
        }

        return $next($request);
    }
}
