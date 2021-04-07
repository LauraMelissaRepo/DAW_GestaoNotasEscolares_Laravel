<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Verificar_Avaliacao_LancarNotas
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $inputAvaliacao = $request->avaliacao;

        if ($inputAvaliacao == 'Avaliacao') {
            return redirect()->to(route('lancarNotas'))->with('faltaAvaliacao', 'Tem que escolher uma avaliação!');
        } else {
            return $next($request);
        }
    }
}
