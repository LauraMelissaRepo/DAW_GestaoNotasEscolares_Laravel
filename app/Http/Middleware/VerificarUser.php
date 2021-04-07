<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $indexRoute = $request->path();
        $userTypeRouteExplode = explode('/', $indexRoute);
        $userTypeRoute = $userTypeRouteExplode[0];
        $userLine = $request->user();
        $user = $userLine['tipo_utilizador'];

        if($userTypeRoute == $user){
            return $next($request);
        }
        else{
            return view(abort(403, 'Página não existente ou não tem acesso à mesma'));
        }

    }
}
