<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Verificar_CadeiraEpoca_ConsultarNotas
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
        $inputCadeira = $request->cadeiraLancarNotas;
        $inputEpoca = $request->epocaLancarNotas;
        if ($inputCadeira == null && $inputEpoca == null) {
            return back()->with('faltaCadeiraEpoca', 'Tem que escolher uma cadeira e uma época!');
        } elseif ($inputCadeira == null && $inputEpoca != null) {
            return back()->with('faltaCadeiraEpoca', 'Tem que escolher uma cadeira!');
        } elseif ($inputCadeira != null && $inputEpoca == null) {
            return back()->with('faltaCadeiraEpoca', 'Tem que escolher uma época!');
        } else {
            return $next($request);
        }
    }
}
