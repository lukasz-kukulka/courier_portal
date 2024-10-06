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
        // dd("CheckIsAccountTypeRegistered middleware", $request);
        //dd( auth()->user() );
        if ( auth()->check() && 
             auth()->user()->account_type === null && 
             auth()->user()->email_verified_at !== null &&
             auth()->user()->name === null &&
             !in_array($request->path(), [
                'logout',
                'register_account',
                'accounts/confirmed_account',
                'add_account_type_and_user_details',
                '/accounts/confirmed_personal_data',
                'accounts/confirmed_account_last'
             ]) ) {
           //return redirect()->route( 'register_account' )->send();
           return redirect()->route( 'confirmed_account' )->send();
        }

        return $next($request);
    }
}