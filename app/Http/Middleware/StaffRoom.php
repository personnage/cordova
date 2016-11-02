<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StaffRoom
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
        // If a user has successfully logged in and has the privilege to
        // execute the query, or he wants to finish the impersonation session.
        if ($this->accessDenied($guard) && ! $this->impersonate($request)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(null, 404);
            } else {
                abort(404);
            }
        }

        return $next($request);
    }

    /**
     * Determine if the user's has access.
     *
     * @param  string|null  $guard
     * @return bool
     */
    protected function accessDenied($guard)
    {
        return Auth::guard($guard)->guest() || ! Auth::guard($guard)->user()->admin || ! 'has not role for access to admin';
    }

    /**
     * Get impersonator user.
     *
     * @param  Illuminate\Http\Request  $request
     * @return User|null
     */
    protected function impersonator($request)
    {
        if ($request->session()->has('impersonator_id')) {
            return User::find($request->session()->get('impersonator_id'));
        }
    }

    /**
     * To determine if the user is impersonate and is willing to end the session.
     *
     * @param  Illuminate\Http\Request  $request
     * @return bool
     */
    protected function impersonate($request)
    {
        if ($request->is('admin/impersonation')) {
            $user = $this->impersonator($request);

            return ! is_null($user) && $user->admin;
        }

        return false;
    }
}
