<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Verificar_AnoLetivoSemestre_ConsultarNotasAluno
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
        $anoInput = $request->anoConsultarNotaAluno;
        $semestreInput = $request->semestreConsultarNotaAluno;
        if ($anoInput == 'Ano' && $semestreInput == 'Semestre') {
            return back()->with('faltaAnoSemestre', 'Tem que escolher um ano letivo e um semestre!');
        } elseif ($anoInput == 'Ano' && $semestreInput != 'Semestre') {
            return back()->with('faltaAnoSemestre', 'Tem que escolher um ano letivo!');
        } elseif ($anoInput != 'Ano' && $semestreInput == 'Semestre') {
            return back()->with('faltaAnoSemestre', 'Tem que escolher um semestre!');
        } else {
            return $next($request);
        }
    }
}
