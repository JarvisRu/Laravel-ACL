<?php

namespace App\Http\Middleware;

use Closure;

class UserHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if($request->user()===NULL)
            return response('Insufficient permission', 401);
        if($request->user()->canDo($permission))
            return $next($request);
        else
            return response('Unauthorized', 401);
    }
}
