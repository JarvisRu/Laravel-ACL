<?php

namespace App\Http\Middleware;

use Closure;

class UserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if($request->user()===NULL)
            return response('Insufficient permission', 401);
        if($request->user()->authorizeRole($role))
            return $next($request);
        else
            return response('Unauthorized', 401);
    }
}
