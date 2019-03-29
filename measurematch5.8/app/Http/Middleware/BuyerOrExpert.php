<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class BuyerOrExpert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check()
            || (Auth::check()
            && (Auth::user()->user_type_id != config('constants.BUYER')
            && Auth::user()->user_type_id != config('constants.EXPERT'))
            )
        )
            return response()->view('errors.404', [], 404);
        return $next($request);
    }
}
