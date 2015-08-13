<?php

namespace Atorscho\Uservel\Groups;

use Closure;

class CheckGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws GroupNotAllowedException
     */
    public function handle($request, Closure $next)
    {
        \Auth::loginUsingId(10);

        $groups = array_slice(func_get_args(), 2);

        if (!current_user_is($groups)) {
            throw new GroupNotAllowedException('You do not have enough permissions to access this page.');
        }

        return $next($request);
    }
}
