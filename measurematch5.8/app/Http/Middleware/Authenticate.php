<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Model\User;
use Carbon\Carbon;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
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
        if (Auth::check()) {
            if(Auth::user()->admin_approval_status === config('constants.REJECTED')){
                return redirect('login')->with(Auth::logout());
            }
            User::updateUser(Auth::user()->id, ['last_login' => Carbon::now()]);
        }
        return $next($request);
    }
}
