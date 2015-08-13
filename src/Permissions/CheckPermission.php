<?php

namespace Atorscho\Uservel\Permissions;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws NotEnoughPermissionsException
     */
    public function handle($request, Closure $next)
    {
        $permissions = array_slice(func_get_args(), 2);

        if (!current_user_can($permissions)) {
            throw new NotEnoughPermissionsException('You do not have enough permissions to access this page.');
        }

        return $next($request);
    }
}
