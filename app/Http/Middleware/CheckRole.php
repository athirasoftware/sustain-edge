<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckRole
{
   /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role) {
        /* if (! $request->user()->hasRole($role)) {
            abort(401, 'This action is unauthorized.');
        }
        return $next($request); */
        $rolesArray = explode("|", $role);
        $isValid = false;
        if(is_array($rolesArray)) {
            foreach($rolesArray as $roleName) {
                if($request->user()->hasRole($roleName)) {
                    $isValid = true;
                    break;
                }
            }
        } else if ($request->user()->hasRole($role)) {
            $isValid = true;
        }
        if($isValid) {
            return $next($request);
        } else {
            abort(401, 'This action is unauthorized.');
        }

        
    }
}
