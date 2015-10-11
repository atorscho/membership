<?php

namespace Atorscho\Membership\Permissions;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param                          $permission
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (!current_user_can($permission)) {
            app()->abort(403, 'You do not have enough permissions to access this page.');
        }

        return $next($request);
    }
}
