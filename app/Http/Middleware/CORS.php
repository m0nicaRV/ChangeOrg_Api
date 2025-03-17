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
<<<<<<< HEAD
        header('Access-Control-Allow-Origin: *');
        header('Access-control-Allow-Origin: Content-Type, X-Auth-Token, Authorization, Origin');
=======
        header ('Access-Control-Allow-Origin: *');
        header('Acces-Control-Alllow-Origin: Content-Type, X-Auth-Token, Authorization, Origin');
>>>>>>> afa4f45a26881a2b6b5efcd5c1c18d9441c1a551
        return $next($request);
    }
}
