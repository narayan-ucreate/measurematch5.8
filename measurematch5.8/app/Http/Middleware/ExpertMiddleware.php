<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ExpertMiddleware
{
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
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
        if(\Auth::check() && \Auth::user()->user_type_id != config('constants.EXPERT')) {
            return response()->view('errors.404', [], 404);
        }

        if(\Auth::check() && \Auth::user()->user_type_id == config('constants.EXPERT') &&
            \Auth::user()->verified_status == config('constants.TRUE') && \Auth::user()->status == config('constants.SIDE_HUSTLER')) {
            return redirect('expert/account-frozen');
        }

        return $next($request);
    }
}
