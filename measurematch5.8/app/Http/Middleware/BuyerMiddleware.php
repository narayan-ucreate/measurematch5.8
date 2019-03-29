<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BuyerMiddleware{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson())
                return response('Unauthorized.', 401);
                
            return redirect()->guest('login');
        }
        
        if(Auth::check() && Auth::user()->user_type_id != config('constants.BUYER'))
            return response()->view('errors.404', [], 404);
        
        return $next($request);
    }
}