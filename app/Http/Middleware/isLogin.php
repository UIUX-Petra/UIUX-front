<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class isLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!session('email')){
            session()->put('url', url()->current());
            return redirect()->route('loginOrRegist');
        }
        return $next($request);
    }
}
