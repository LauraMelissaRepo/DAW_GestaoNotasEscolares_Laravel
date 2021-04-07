<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Verificar_Cadeira_MarcarAvaliacao
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
        $inputCadeira = $request->filterCadeira;

        if ($inputCadeira == 'nothing'){
            return back()->with('faltaCadeira', 'Tem que escolher uma cadeira!');
        }else{
            return $next($request);
        }
    }
}
