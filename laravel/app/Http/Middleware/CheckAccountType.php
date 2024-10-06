<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$allowedAccountTypes)
    {
        $user = Auth::user();

        if( $user->account_type === 'full' ) {
            return $next($request);
        }
        
        if ( $user && in_array( $user->account_type, $allowedAccountTypes ) ) {
            return $next($request);
        }

        return redirect()->route('no_access');
    }
}
