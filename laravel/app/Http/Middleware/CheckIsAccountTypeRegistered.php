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
        // dd($request);
        if ( auth()->check() && auth()->user()->account_type === null && auth()->user()->email_verified_at !== null &&
             !in_array($request->path(), [
                'logout',
                'register_account',
                'accounts/confirmed_account',
                'create_person_data',
                '/accounts/confirmed_personal_data',
                'accounts/confirmed_account_last'
             ]) ) {
           return redirect()->route( 'register_account' )->send();
        }

        return $next($request);
    }
}