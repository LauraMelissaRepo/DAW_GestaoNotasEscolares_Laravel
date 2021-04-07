<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Verificar_Cadeira_ConsultarNotasAluno
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
        $inputCadeira = $request->cadeiraConsultarNotaAluno;
        if ($inputCadeira == 'Cadeira'){
            return redirect()->to(route('consultarNotasAluno'))->with('faltaCadeira', 'Tem que escolher uma Cadeira!');

        }
        else{
            return $next($request);
        }
    }
}
