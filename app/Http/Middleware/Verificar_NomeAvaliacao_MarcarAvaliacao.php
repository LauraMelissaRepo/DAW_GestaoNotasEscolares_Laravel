<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Verificar_NomeAvaliacao_MarcarAvaliacao
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
        $inputNomeAvaliacao = $request->descricao_avalicao;

        if ($inputNomeAvaliacao == null){
            return redirect()->to(route('marAval'))->with('faltaDescricao', 'A Avaliação não foi adicionada à base de dados, precisa dar uma descrição à avaliação!');
        }else{
            return $next($request);
        }
    }
}
