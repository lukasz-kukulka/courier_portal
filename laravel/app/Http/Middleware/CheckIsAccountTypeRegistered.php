<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CheckIsAccountTypeRegistered
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ( auth()->check() && auth()->user()->account_type === null &&
             !in_array($request->path(), [ 'logout' ]) &&
             !Str::startsWith( $request->path(), 'accounts/') ) {
           return redirect()->route( 'account_register' );
        }

        return $next($request);
    }
}
