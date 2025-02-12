<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        header ('Access-Control-Allow-Origin: *');
        header('Acces-Control-Alllow-Origin: Content-Type, X-Auth-Token, Authorization, Origin');
        return $next($request);
    }
}
