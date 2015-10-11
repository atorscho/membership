<?php

namespace Atorscho\Membership\Groups;

use Closure;

class CheckGroupBelonging
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $group
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $group)
    {
        if (!current_user_is($group)) {
            app()->abort(403, 'You cannot access this page.');
        }

        return $next($request);
    }
}
