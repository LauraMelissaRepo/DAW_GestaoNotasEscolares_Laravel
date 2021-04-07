<?php

namespace App\Http\Controllers;

use App\Models\AnoLetivo;
use App\Models\Avaliacao;
use App\Models\Classificacao;
use App\Models\Curso;
use App\Models\Inscricao_Avaliacao;
use App\Models\Inscricao_Matricula;
use App\Models\Semestre;
use App\Models\UC;
use App\Models\UC_Funcionamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\Psr7\str;

class AlunoController extends Controller
{

    function __construct(){
        //Middlewares para verificar se o login está feito e se o utilizador pode estar na página onde quer ir
        $this->middleware('auth');
        $this->middleware('verificarUser');
    }

    public function perfilAluno()
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //nome aluno
        $nomeUser = $linhaUtilizador['name'];
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];
        //curso aluno
        $linhaInscricaoMatricula = Inscricao_Matricula::where('aluno_id', '=', $numeroAluno)->first();
        $idInscricaoMatricula = $linhaInscricaoMatricula['id'];

        $linhaUCFuncionamento = UC_Funcionamento::where('incricaoMatricula_id', '=', $idInscricaoMatricula)->first();
        $idUC = $linhaUCFuncionamento['uc_id'];

        $linhaUcs = UC::where('id', '=', $idUC)->first();
        $idCurso = $linhaUcs['curso_id'];

        $linhaCurso = Curso::where('id', '=', $idCurso)->first();
        $nomeCurso = $linhaCurso['nome_curso'];

        return view('pages.aluno.perfilAluno', [
            'namehtml' => $nomeUser,
            'numerohtml' => $numeroAluno,
            'cursohtml' => $nomeCurso
        ]);
    }


    public function consultarNotas()
    {
        //buscar anos letivos
        $linhasAnosLetivos = AnoLetivo::all();
        foreach ($linhasAnosLetivos as $linhaAnoLetivo) {
            $idsAnosLetivos[] = $linhaAnoLetivo['id'];
            $stringsAnosLetivos[] = date('Y', strtotime($linhaAnoLetivo['anoletivo_inicio'])) . '/' . date('Y', strtotime($linhaAnoLetivo['anoletivo_fim']));
        }

        $placeHolderAnoLetivo = '';
        $placeHolderSemestre = '';

        return view('pages.aluno.consultarNotas', [
            'arrayStringAnosLetivos' => $stringsAnosLetivos,
            'arrayIdsAnosLetivos' => $idsAnosLetivos,
            'placeHolderAnoLetivo' => $placeHolderAnoLetivo,
            'placeHolderSemestre' => $placeHolderSemestre
        ]);
    }

    public function mostrarCadeiras(Request $r)
    {
        //código repetido para mostrar o blade acima
        $linhasAnosLetivos = AnoLetivo::all();
        foreach ($linhasAnosLetivos as $linhaAnoLetivo) {
            $idsAnosLetivos[] = $linhaAnoLetivo['id'];
            $stringsAnosLetivos[] = date('Y', strtotime($linhaAnoLetivo['anoletivo_inicio'])) . '/' . date('Y', strtotime($linhaAnoLetivo['anoletivo_fim']));
        }
        //fim do código repetido

        //receber os inputs dos anos e semestre
        $inputStringIdAno = $r->anoConsultarNotaAluno;
        $inputStringSemestre = $r->semestreConsultarNotaAluno;
        //converter o input do Ano par ID
        for ($i = 0; $i < count($stringsAnosLetivos); $i++) {
            if ($stringsAnosLetivos[$i] == $inputStringIdAno) {
                $inputIdAno = $i + 1;
            }
        }
        //converter o input do Semestre par ID
        if ($inputStringSemestre == '1º Semestre') {
            $inputIdSemestre = 1;
        } elseif ($inputStringSemestre == '2º Semestre') {
            $inputIdSemestre = 2;
        }

        //substituir os placeholders para os inputs se manterem os mesmos após uma escolha
        $placeHolderAnoLetivo = $inputStringIdAno;
        $placeHolderSemestre = $inputStringSemestre;

        //ir buscar as cadeiras segundo os filtros escolhidos
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];

        //ir buscar os ids da tabela inscricoes as ucs do o aluno
        $linhasInscricoesMatriculas = Inscricao_Matricula::all()->where('aluno_id', '=', $numeroAluno);
        foreach ($linhasInscricoesMatriculas as $linhaInscricaoMatricula) {
            $idsInscricoesMatriculas[] = $linhaInscricaoMatricula['id'];
        }

        //ir buscar todas as informacoes associadas a estas inscricoes
        $linhasUcsFuncionamento = array();
        foreach ($idsInscricoesMatriculas as $idInscricaoMatricula) {
            $linhasUcsFuncionamento = UC_Funcionamento::where('incricaoMatricula_id', '=', $idInscricaoMatricula)->get();
        }

        //filtragem de todas as inscricoes pelo ano letivo escolhido
        $linhasUcsFuncionamentoAno = array();
        for ($j = 0; $j < count($linhasUcsFuncionamento); $j++) {
            $linhaUcFuncionamento = $linhasUcsFuncionamento[$j];
            if ($linhaUcFuncionamento['anoletivo_id'] == $inputIdAno) {
                $linhasUcsFuncionamentoAno[] = $linhaUcFuncionamento;
            }
        }

        //filtragem pelo semestre escolhido
        $linhasUcsFuncionamentoAnoSemestre = array();
        for ($h = 0; $h < count($linhasUcsFuncionamentoAno); $h++) {
            $linhaUcFuncionamentoAno = $linhasUcsFuncionamentoAno[$h];
            $idUC = $linhaUcFuncionamentoAno['uc_id'];
            $linhaUC = UC::where('id', '=', $idUC)->first();
            if ($linhaUC['semestre_id'] == $inputIdSemestre) {
                $linhasUcsFuncionamentoAnoSemestre[] = $linhaUC['nome_uc'];
            }
        }

        //place holder para a cadeira escolhida
        $placeHolderCadeira = '';


        return view('layouts.aluno.mostrarCadeiraAlunoConsultarNotas', [
            'inputIdStringAno' => $inputStringIdAno,
            'inputStringSemestre' => $inputStringSemestre,
            'arrayStringAnosLetivos' => $stringsAnosLetivos,
            'arrayIdsAnosLetivos' => $idsAnosLetivos,
            'placeHolderAnoLetivo' => $placeHolderAnoLetivo,
            'placeHolderSemestre' => $placeHolderSemestre,
            'arrayLinhasUcsFiltradas' => $linhasUcsFuncionamentoAnoSemestre,
            'placeHolderCadeira' => $placeHolderCadeira
        ]);
    }

    public function consultarNotasAlunofilter(Request $r)
    {
        //código repetido para mostrar o blade acima
        $linhasAnosLetivos = AnoLetivo::all();
        foreach ($linhasAnosLetivos as $linhaAnoLetivo) {
            $idsAnosLetivos[] = $linhaAnoLetivo['id'];
            $stringsAnosLetivos[] = date('Y', strtotime($linhaAnoLetivo['anoletivo_inicio'])) . '/' . date('Y', strtotime($linhaAnoLetivo['anoletivo_fim']));
        }

        //receber os inputs dos anos e semestre
        $inputStringIdAno = $r->anoConsultarNotaAluno;
        $inputStringSemestre = $r->semestreConsultarNotaAluno;
        $placeHolderCadeira = $r->cadeiraConsultarNotaAluno;
        //converter o input do Ano par ID
        for ($i = 0; $i < count($stringsAnosLetivos); $i++) {
            if ($stringsAnosLetivos[$i] == $inputStringIdAno) {
                $inputIdAno = $i + 1;
            }
        }
        //converter o input do Semestre par ID
        if ($inputStringSemestre == '1º Semestre') {
            $inputIdSemestre = 1;
        } elseif ($inputStringSemestre == '2º Semestre') {
            $inputIdSemestre = 2;
        }

        //substituir os placeholders para os inputs se manterem os mesmos após uma escolha
        $placeHolderAnoLetivo = $inputStringIdAno;
        $placeHolderSemestre = $inputStringSemestre;

        //ir buscar as cadeiras segundo os filtros escolhidos
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];

        //ir buscar os ids da tabela inscricoes as ucs do o aluno
        $linhasInscricoesMatriculas = Inscricao_Matricula::all()->where('aluno_id', '=', $numeroAluno);
        foreach ($linhasInscricoesMatriculas as $linhaInscricaoMatricula) {
            $idsInscricoesMatriculas[] = $linhaInscricaoMatricula['id'];
        }

        //ir buscar todas as informacoes associadas a estas inscricoes
        foreach ($idsInscricoesMatriculas as $idInscricaoMatricula) {
            $linhasUcsFuncionamento = UC_Funcionamento::where('incricaoMatricula_id', '=', $idInscricaoMatricula)->get();
        }

        //filtragem de todas as inscricoes pelo ano letivo escolhido
        for ($j = 0; $j < count($linhasUcsFuncionamento); $j++) {
            $linhaUcFuncionamento = $linhasUcsFuncionamento[$j];
            if ($linhaUcFuncionamento['anoletivo_id'] == $inputIdAno) {
                $linhasUcsFuncionamentoAno[] = $linhaUcFuncionamento;
            }
        }

        //filtragem pelo semestre escolhido
        $linhasUcsFuncionamentoAnoSemestre = array();
        for ($h = 0; $h < count($linhasUcsFuncionamentoAno); $h++) {
            $linhaUcFuncionamentoAno = $linhasUcsFuncionamentoAno[$h];
            $idUC = $linhaUcFuncionamentoAno['uc_id'];
            $linhaUC = UC::where('id', '=', $idUC)->first();
            if ($linhaUC['semestre_id'] == $inputIdSemestre) {
                $linhasUcsFuncionamentoAnoSemestre[] = $linhaUC['nome_uc'];
            }
        }
        //fim do código repetido

        //ir buscar todas as avaliacoes da uc escolhida
        //identificar qual o id da uc selecionada
        $linhaUcChoosen = UC::where('nome_uc', '=', $placeHolderCadeira)->first();
        $idUcChoosen = $linhaUcChoosen['id'];

        //ir buscar todas as avaliacoes desta UC
        $linhasAvaliacoesUC = Avaliacao::where('uc_id', '=', $idUcChoosen)->get();
        //ir buscar todos os ids das avaliacoes da Uc escolhida
        $idsLinhasAvaliacoesUC = array();
        for ($l = 0; $l < count($linhasAvaliacoesUC); $l++) {
            $linhaAvaliacaoUC = $linhasAvaliacoesUC[$l];
            $idsLinhasAvaliacoesUC[] = $linhaAvaliacaoUC['id'];
        }
        //filtragem para apenas receber as inscricoes do aluno às avaliacoes da uc e segundo a data. Só irá receber
        //as avaliacoes em que a data de inscricao seja anterior à data corrente
        $linhasAvaliacoesUCInscritas = array();
        $inscricoesAvaliacao = array();
        //inicio do ano letivo input
        $anosInput = explode('/', $inputStringIdAno);
        //id Semestre
        $idSemestre = substr($inputStringSemestre, 0, 1);
        $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
        //datas dos semestres de acordo com o semestre e o ano escolhido
        $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
        $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
        if ($idSemestre == 1) {
            $dataInicioSemestreInput = date('Y-m-d', strtotime($anosInput[0] . '-' . $dataInicioSemestre));
            $dataFimSemestreInput = date('Y-m-d', strtotime($anosInput[1] . '-' . $dataFimSemestre));
        } else {
            $dataInicioSemestreInput = date('Y-m-d', strtotime($anosInput[1] . '-' . $dataInicioSemestre));
            $dataFimSemestreInput = date('Y-m-d', strtotime($anosInput[1] . '-' . $dataFimSemestre));
        }

        //filtragem das inscricoes as avaliacoes segundo o inicio e o fim do semestre do input com o ano letivo input
        $linhasAvaliacoesUCInscritas = array();
        for ($o = 0; $o < count($idsLinhasAvaliacoesUC); $o++) {
            $idAval = $idsLinhasAvaliacoesUC[$o];
            $linhasAvaliacoesUCInscritas[] = Inscricao_Avaliacao::where('avaliacao_id', '=', $idAval)->first();
        }
        $inscricoesAvaliacao = array();
        foreach ($linhasAvaliacoesUCInscritas as $linhaAvaliacaoUCInscrita) {
            if ($linhaAvaliacaoUCInscrita != null
                && $linhaAvaliacaoUCInscrita['aluno_id'] == $numeroAluno
                && $linhaAvaliacaoUCInscrita['data'] >= $dataInicioSemestreInput
                && $linhaAvaliacaoUCInscrita['data'] <= $dataFimSemestreInput) {
                $inscricoesAvaliacao[] = $linhaAvaliacaoUCInscrita;
            }
        }

        //ir buscar dados para preencher a lista das Avaliacoes a que o aluno está inscrito da uc escolhida
        //Descricao Avaliacao
        $nomeAval = array();
        $epocaAval = array();
        foreach ($inscricoesAvaliacao as $inscricaoAvaliacao) {
            $linhaAval = Avaliacao::where('id', '=', $inscricaoAvaliacao['avaliacao_id'])->first();
            $nomeAval[] = $linhaAval['nome_avaliacao'];
            if ($linhaAval['epoca'] == 1) {
                $epocaAval[] = 'Normal';
            } else {
                $epocaAval[] = 'Recurso';
            }
        }
//        dd($nomeAval, $epocaAval);

        //Nota Avaliacao
        $notaAval = array();
        $dataLancaAval = array();
        $estadoAval = array();
        foreach ($inscricoesAvaliacao as $inscricaoAvaliacao) {
            $linhaClass = Classificacao::where('id', '=', $inscricaoAvaliacao['classificacao_id'])->first();
            if ($linhaClass['valor_classificacao'] == null) {
                $notaAval[] = 'Por Lançar';
            } else {
                $notaAval[] = $linhaClass['valor_classificacao'];
            }
            if ($linhaClass['data_lancamento'] == null) {
                $dataLancaAval[] = 'Por Lançar';
            } else {
                $dataLancaAval[] = $linhaClass['data_lancamento'];
            }
            if ($linhaClass['valor_classificacao'] == null) {
                $estadoAval[] = 'Por Lançar';
            } elseif ($linhaClass['valor_classificacao'] > 9.5) {
                $estadoAval[] = 'Aprovado';
            } else {
                $estadoAval[] = 'Reprovado';
            }
        }
//        dd($notaAval, $dataLancaAval, $estadoAval);


        return view('layouts.aluno.notasAluno', [
            'inputIdStringAno' => $inputStringIdAno,
            'inputStringSemestre' => $inputStringSemestre,
            'arrayStringAnosLetivos' => $stringsAnosLetivos,
            'arrayIdsAnosLetivos' => $idsAnosLetivos,
            'placeHolderAnoLetivo' => $placeHolderAnoLetivo,
            'placeHolderSemestre' => $placeHolderSemestre,
            'arrayLinhasUcsFiltradas' => $linhasUcsFuncionamentoAnoSemestre,
            'placeHolderCadeira' => $placeHolderCadeira,
            'arrayNomeAval' => $nomeAval,
            'arrayNotaAval' => $notaAval,
            'arrayDataLancaAval' => $dataLancaAval,
            'arrayEpocaAval' => $epocaAval,
            'arrayEstadoAval' => $estadoAval
        ]);
    }

    public function consultarNotasAlunoTodas()
    {
        //codigo repetido para os selects funcionarem
        //buscar anos letivos
        $linhasAnosLetivos = AnoLetivo::all();
        foreach ($linhasAnosLetivos as $linhaAnoLetivo) {
            $idsAnosLetivos[] = $linhaAnoLetivo['id'];
            $stringsAnosLetivos[] = date('Y', strtotime($linhaAnoLetivo['anoletivo_inicio'])) . '/' . date('Y', strtotime($linhaAnoLetivo['anoletivo_fim']));
        }

        $placeHolderAnoLetivo = '';
        $placeHolderSemestre = '';
        //fim do codigo repetido

        //ir buscar todas as inscricoes do aluno as avaliacoes
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];

        //ir buscra todas as inscricoes do aluno
        $linhasInscricoesAval = array();
        $linhasInscricoesAval = Inscricao_Avaliacao::where('aluno_id', '=', $numeroAluno)->get();
        $nomeAval = array();
        $epocaAval = array();
        $ucNomeAval = array();
        foreach ($linhasInscricoesAval as $linhaInscricaoAval) {
            $linhaAval = Avaliacao::where('id', '=', $linhaInscricaoAval['avaliacao_id'])->first();
            $idUC = $linhaAval['uc_id'];
            $linhaUC = UC::where('id', '=', $idUC)->first();
            $ucNomeAval[] = $linhaUC['nome_uc'];
            $nomeAval[] = $linhaAval['nome_avaliacao'];
            if ($linhaAval['epoca'] == 1) {
                $epocaAval[] = 'Normal';
            } else {
                $epocaAval[] = 'Recurso';
            }
        }

        $notaAval = array();
        $dataLancaAval = array();
        $estadoAval = array();
        foreach ($linhasInscricoesAval as $linhaInscricaoAval) {
            $linhaClass = Classificacao::where('id', '=', $linhaInscricaoAval['classificacao_id'])->first();
            if ($linhaClass['valor_classificacao'] == null) {
                $notaAval[] = 'Por Lançar';
            } else {
                $notaAval[] = $linhaClass['valor_classificacao'];
            }
            if ($linhaClass['data_lancamento'] == null) {
                $dataLancaAval[] = 'Por Lançar';
            } else {
                $dataLancaAval[] = $linhaClass['data_lancamento'];
            }
            if ($linhaClass['valor_classificacao'] == null) {
                $estadoAval[] = 'Por Lançar';
            } elseif ($linhaClass['valor_classificacao'] >= 9.5) {
                $estadoAval[] = 'Aprovado';
            } else {
                $estadoAval[] = 'Reprovado';
            }
        }


        return view('layouts.aluno.notasAlunoTodas', [
            'arrayStringAnosLetivos' => $stringsAnosLetivos,
            'arrayIdsAnosLetivos' => $idsAnosLetivos,
            'placeHolderAnoLetivo' => $placeHolderAnoLetivo,
            'placeHolderSemestre' => $placeHolderSemestre,
            'arrayNomeAval' => $nomeAval,
            'arrayNotaAval' => $notaAval,
            'arrayDataLancaAval' => $dataLancaAval,
            'arrayEpocaAval' => $epocaAval,
            'arrayEstadoAval' => $estadoAval,
            'arrayNomesUcAval' => $ucNomeAval
        ]);
    }

    public function inscreverExames()
    {
        return view('pages.aluno.inscreverExame');
    }


    public function mostrarRecursos()
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];

        //ir buscar a data de inicio e fim do semestre corrente de acordo com a data do dia
        $dataCorrente = date('Y-m-d');
        $mesDataCorrente = date('m', strtotime($dataCorrente));
        $anoDataCorrente = date('Y', strtotime($dataCorrente));
        if ($mesDataCorrente >= 9 && $mesDataCorrente <= 12) {
            $idSemestre = 1;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataInicioSemestre));
            $anoDataCorrenteChange = date('Y', strtotime($anoDataCorrente . '+1 year'));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataFimSemestre));
        } elseif ($mesDataCorrente >= 1 && $mesDataCorrente <= 2) {
            $idSemestre = 1;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $anoDataCorrenteChange = date('Y', strtotime('-1 year'));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataInicioSemestre));
            $anoDataCorrenteChange = date('Y', strtotime($anoDataCorrente));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataFimSemestre));
        } elseif ($mesDataCorrente >= 3 && $mesDataCorrente <= 7) {
            $idSemestre = 2;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataInicioSemestre));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataFimSemestre));
        }

        //ir buscar todas inscricoes do aluno com filtragem pela data de inicio e fim do semestre e id do aluno
        $linhasInscricoesAvaliacao = Inscricao_Avaliacao::where('aluno_id', '=', $numeroAluno)->get();
        $linhasInscricoesAvaliacaoData = array();
        foreach ($linhasInscricoesAvaliacao as $linha) {
            if ($linha['data'] >= $inicioSemestre
                && $linha['data'] <= $fimSemestre) {
                $linhasInscricoesAvaliacaoData[] = $linha;
            }
        }

        //caso encontre uma negativa em epoca normal pode ir a recurso
        $linhasInscricoesAvaliacaoDataNegativa = array();
        foreach ($linhasInscricoesAvaliacaoData as $linha) {
            $linhaClass = Classificacao::where('id', '=', $linha['classificacao_id'])->first();
            if ($linhaClass['valor_classificacao'] != null
                && $linhaClass['valor_classificacao'] < 9.5) {
                $linhaAval = Avaliacao::where('id', '=', $linha['avaliacao_id'])->first();
                if ($linhaAval['epoca'] == 1) {
                    $idUC = $linhaAval['uc_id'];
                    $linhaUC = UC::where('id', '=', $idUC)->first();
                    $linhasInscricoesAvaliacaoDataNegativa[] = $linhaUC['nome_uc'];
                }
            }
        }
        $nomesUcsRecurso = array_unique($linhasInscricoesAvaliacaoDataNegativa);

        //conversao dos nomes para ids dos nomes das cadeiras com negativa
        $idsRecursos = array();
        foreach ($nomesUcsRecurso as $recurso){
            $linhaUC = UC::where('nome_uc', '=', $recurso)->first();
            $idsRecursos[] = $linhaUC['id'];
        }
        //ir buscar todos os recursos dessa cadeira e filtrar pela data deste semestre
        $recursosDisponiveis = array();
        foreach ($idsRecursos as $id){
            $linhas = Avaliacao::where([['uc_id', '=', $id], ['epoca', '=', 2]])->get();
            foreach ($linhas as $l){
                if ($l['data_avaliacao'] >= $inicioSemestre
                    && $l['data_avaliacao'] <= $fimSemestre){
                    $recursosDisponiveis[] = $l['id'];
                }
            }
        }

        //converter as linhas de inscricao em idAvaliacoes
        $idsAvaliacoesInscricoes = array();
        foreach ($linhasInscricoesAvaliacaoData as $linha){
            $idsAvaliacoesInscricoes[] = $linha['avaliacao_id'];
        }

        //se o id do recurso nao tiver na inscricao é porque ele pode se inscrever. Caso nao esteja, é acrescentado o id do recurso
        $idsRecursosPodeInscrever = array();
        foreach ($recursosDisponiveis as $recurso){
            if(!in_array($recurso, $idsAvaliacoesInscricoes)){
                $idsRecursosPodeInscrever[] = $recurso;
            }
        }

        //conversao dos ids para nomes de cadeiras dos recurso a que se pode inscrever
        $nomesUcsRecursoFinal = array();
        foreach ($idsRecursosPodeInscrever as $id){
            $linhaAval = Avaliacao::where('id', '=', $id)->first();
            $linhaUC = UC::where('id', '=', $linhaAval['uc_id'])->first();
            $nomesUcsRecursoFinal[] = $linhaUC['nome_uc'];
        }

        $nomesUcsRecursoFinal = array_unique($nomesUcsRecursoFinal);

        $tipoRecurso = 'Recursos';

        return view('layouts.aluno.examesAluno', [
            'arrayUC' => $nomesUcsRecursoFinal,
            'tipo' => $tipoRecurso
        ]);
    }

    public function mostrarMelhorias()
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];

        //ir buscar a data de inicio e fim do semestre corrente de acordo com a data do dia
        $dataCorrente = date('Y-m-d');
        $mesDataCorrente = date('m', strtotime($dataCorrente));
        $anoDataCorrente = date('Y', strtotime($dataCorrente));
        if ($mesDataCorrente >= 9 && $mesDataCorrente <= 12) {
            $idSemestre = 1;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataInicioSemestre));
            $anoDataCorrenteChange = date('Y', strtotime($anoDataCorrente . '+1 year'));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataFimSemestre));
        } elseif ($mesDataCorrente >= 1 && $mesDataCorrente <= 2) {
            $idSemestre = 1;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $anoDataCorrenteChange = date('Y', strtotime('-1 year'));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataInicioSemestre));
            $anoDataCorrenteChange = date('Y', strtotime($anoDataCorrente));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataFimSemestre));
        } elseif ($mesDataCorrente >= 3 && $mesDataCorrente <= 7) {
            $idSemestre = 2;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataInicioSemestre));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataFimSemestre));
        }

        //ir buscar todas inscricoes do aluno com filtragem pela data de inicio e fim do semestre e id do aluno
        $linhasInscricoesAvaliacao = Inscricao_Avaliacao::where('aluno_id', '=', $numeroAluno)->get();
        $linhasInscricoesAvaliacaoData = array();
        foreach ($linhasInscricoesAvaliacao as $linha) {
            if ($linha['data'] >= $inicioSemestre
                && $linha['data'] <= $fimSemestre) {
                $linhasInscricoesAvaliacaoData[] = $linha;
            }
        }

        $linhasInscricoesAvaliacaoDataCompare = $linhasInscricoesAvaliacaoData;
        $ucsSemNegativas = array();
        foreach ($linhasInscricoesAvaliacaoData as $linha) {
            $counterNegativas = 0;
            //para cada inscricao ir buscar o id da uc
            $linhaAval = Avaliacao::where('id', '=', $linha['avaliacao_id'])->first();
            $idUc = $linhaAval['uc_id'];
            //voltar a percorrer as inscricoes e se a avaliacao for de epoca normal e a mesma uc verifica se há negativas
            //caso encontre, aumenta o counter. caso o counter permanecer a 0 é porque nao tem negativas e acescenta o nome da uc ao array
            foreach ($linhasInscricoesAvaliacaoDataCompare as $linhaCompare) {
                $linhaAvalCompare = Avaliacao::where('id', '=', $linhaCompare['avaliacao_id'])->first();
                $linhaClassCompare = Classificacao::where('id', '=', $linhaCompare['classificacao_id'])->first();
                if ($linhaAvalCompare['uc_id'] == $idUc
                    && $linhaAvalCompare['epoca'] == 1
                    && $linhaClassCompare['valor_classificacao'] != null
                    && $linhaClassCompare['valor_classificacao'] < 9.5) {
                    $counterNegativas++;
                }
            }
            if ($counterNegativas == 0) {
                $linhaUc = UC::where('id', '=', $idUc)->first();
                $ucsSemNegativas[] = $linhaUc['nome_uc'];
            }
        }
        $nomesUcsMelhoria = array_unique($ucsSemNegativas);

        //conversao dos nomes para ids dos nomes das cadeiras com negativa
        $idsMelhoria = array();
        foreach ($nomesUcsMelhoria as $melhoria){
            $linhaUC = UC::where('nome_uc', '=', $melhoria)->first();
            $idsMelhoria[] = $linhaUC['id'];
        }
        //ir buscar todos os recursos dessa cadeira e filtrar pela data deste semestre
        $melhoriasDisponiveis = array();
        foreach ($idsMelhoria as $id){
            $linhas = Avaliacao::where([['uc_id', '=', $id], ['epoca', '=', 2]])->get();
            foreach ($linhas as $l){
                if ($l['data_avaliacao'] >= $inicioSemestre
                    && $l['data_avaliacao'] <= $fimSemestre){
                    $melhoriasDisponiveis[] = $l['id'];
                }
            }
        }

        //converter as linhas de inscricao em idAvaliacoes
        $idsAvaliacoesInscricoes = array();
        foreach ($linhasInscricoesAvaliacaoData as $linha){
            $idsAvaliacoesInscricoes[] = $linha['avaliacao_id'];
        }

        //se o id do recurso nao tiver na inscricao é porque ele pode se inscrever. Caso nao esteja, é acrescentado o id do recurso
        $idsMelhoriasPodeInscrever = array();
        foreach ($melhoriasDisponiveis as $melhoria){
            if(!in_array($melhoria, $idsAvaliacoesInscricoes)){
                $idsMelhoriasPodeInscrever[] = $melhoria;
            }
        }

        //conversao dos ids para nomes de cadeiras dos recurso a que se pode inscrever
        $nomesUcsMelhoriaFinal = array();
        foreach ($idsMelhoriasPodeInscrever as $id){
            $linhaAval = Avaliacao::where('id', '=', $id)->first();
            $linhaUC = UC::where('id', '=', $linhaAval['uc_id'])->first();
            $nomesUcsMelhoriaFinal[] = $linhaUC['nome_uc'];
        }

        $nomesUcsMelhoriaFinal = array_unique($nomesUcsMelhoriaFinal);
        $tipoMelhoria = 'Melhorias';


        return view('layouts.aluno.examesAluno', [
            'arrayUC' => $nomesUcsMelhoriaFinal,
            'tipo' => $tipoMelhoria
        ]);
    }

    public function insertRecursoMelhoria(Request $r)
    {
        $uc = $r->input('uc');

        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];

        //ir buscar os ids das ucs
        $idsUc = array();
        foreach ($uc as $eachUC) {
            $linhaUc = UC::where('nome_uc', '=', $eachUC)->first();
            $idsUc[] = $linhaUc['id'];
        }

        //ir buscar os ids de todas as avaliacoes com epoca recurso, id da uc, data estar dentro do semestre atual
        //Data Semestre atual
        $dataCorrente = date('Y-m-d');
        $mesDataCorrente = date('m', strtotime($dataCorrente));
        $anoDataCorrente = date('Y', strtotime($dataCorrente));
        if ($mesDataCorrente >= 9 && $mesDataCorrente <= 12) {
            $idSemestre = 1;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataInicioSemestre));
            $anoDataCorrenteChange = date('Y', strtotime($anoDataCorrente . '+1 year'));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataFimSemestre));
        } elseif ($mesDataCorrente >= 1 && $mesDataCorrente <= 2) {
            $idSemestre = 1;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $anoDataCorrenteChange = date('Y', strtotime('-1 year'));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataInicioSemestre));
            $anoDataCorrenteChange = date('Y', strtotime($anoDataCorrente));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataFimSemestre));
        } elseif ($mesDataCorrente >= 3 && $mesDataCorrente <= 7) {
            $idSemestre = 2;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataInicioSemestre));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataFimSemestre));
        }
        //filtragem das avaliacoes de recurso dessa cadeira dentro do semestre
        $linhasAvalRecursoUc = array();
        foreach ($idsUc as $idUc) {
            $linhasAvalRecursoUc = Avaliacao::where([['uc_id', '=', $idUc], ['epoca', '=', 2]])->get();
        }
        //filtragem das avaliacoes de recurso para apenas ter os recursos do semestre corrente
        for ($i = 0; $i < count($linhasAvalRecursoUc); $i++) {
            $linha = $linhasAvalRecursoUc[$i];
            if ($linha['data_avaliacao'] < $inicioSemestre && $linha['data_avaliacao'] > $fimSemestre) {
                unset($linhasAvalRecursoUc[$i]);
            }
        }
        //receber os ids das avaliacoes filtradas
        $idsAvaliacaoRecursoUc = array();
        foreach ($linhasAvalRecursoUc as $linha) {
            $idsAvaliacaoRecursoUc[] = $linha['id'];
        }

        //insert nas inscricoes e classificacoes com base na inscricao automatica de epoca normal (controler docente)
        foreach ($idsAvaliacaoRecursoUc as $id) {
            //ir buscar todos os ultimos ids inseridos para os inserts baterem certo mais tarde com um counter
            $idLastClass = Classificacao::all()->last();
            $idLastInsc = Inscricao_Avaliacao::all()->last();

            //insert na table Classificacao para esta estar associada à avaliacao marcada
            $insertTClassi = new Classificacao;
            $insertTClassi->valor_classificacao = null;
            $insertTClassi->data_lancamento = null;
            $insertTClassi->incricao_avaliacao_id = $idLastInsc['id'] + 1;
            $insertTClassi->save();

            //insert automatico caso seja epoca normal na table Inscricao_Avaliacao para todos os alunos inscritos à cadeira onde a avaliacao foi marcada
            $insertTInsAva = new Inscricao_Avaliacao;
            $insertTInsAva->data = date('Y-m-d');
            $insertTInsAva->avaliacao_id = $id;
            $insertTInsAva->aluno_id = $numeroAluno;
            $insertTInsAva->classificacao_id = $idLastClass['id'] + 1;
            $insertTInsAva->save();
        }

        return view('pages.aluno.inscreverExame');
    }

    public function mostrarTodos()
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];

        //ir buscar a data de inicio e fim do semestre corrente de acordo com a data do dia
        $dataCorrente = date('Y-m-d');
        $mesDataCorrente = date('m', strtotime($dataCorrente));
        $anoDataCorrente = date('Y', strtotime($dataCorrente));
        if ($mesDataCorrente >= 9 && $mesDataCorrente <= 12) {
            $idSemestre = 1;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataInicioSemestre));
            $anoDataCorrenteChange = date('Y', strtotime($anoDataCorrente . '+1 year'));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataFimSemestre));
        } elseif ($mesDataCorrente >= 1 && $mesDataCorrente <= 2) {
            $idSemestre = 1;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $anoDataCorrenteChange = date('Y', strtotime('-1 year'));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataInicioSemestre));
            $anoDataCorrenteChange = date('Y', strtotime($anoDataCorrente));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrenteChange . '-' . $dataFimSemestre));
        } elseif ($mesDataCorrente >= 3 && $mesDataCorrente <= 7) {
            $idSemestre = 2;
            $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
            $dataInicioSemestre = date('m-d', strtotime($linhaSemestre['data_inicio']));
            $dataFimSemestre = date('m-d', strtotime($linhaSemestre['data_fim']));
            $inicioSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataInicioSemestre));
            $fimSemestre = date('Y-m-d', strtotime($anoDataCorrente . '-' . $dataFimSemestre));
        }

        //ir buscar todas inscricoes do aluno com filtragem pela data de inicio e fim do semestre e id do aluno
        $linhasInscricoesAvaliacao = Inscricao_Avaliacao::where('aluno_id', '=', $numeroAluno)->get();
        $linhasInscricoesAvaliacaoData = array();
        foreach ($linhasInscricoesAvaliacao as $linha) {
            if ($linha['data'] >= $inicioSemestre
                && $linha['data'] <= $fimSemestre) {
                $linhasInscricoesAvaliacaoData[] = $linha;
            }
        }

        //filtragem para apenas ir buscar os recursos inscritos
        $nomesInscricoesAvaliacaoDataRecursos = array();
        foreach ($linhasInscricoesAvaliacaoData as $linha){
            $linhaAval = Avaliacao::where('id', '=', $linha['avaliacao_id'])->first();
            if($linhaAval['epoca'] == 2){
                $linha_UC = UC::where('id', '=', $linhaAval['uc_id'])->first();
                $nomeUC = $linha_UC['nome_uc'];
                $nomesInscricoesAvaliacaoDataRecursos[] = $nomeUC;
            }
        }

        $nomesInscricoesAvaliacaoDataRecursos = array_unique($nomesInscricoesAvaliacaoDataRecursos);
        $tipo = 'Inscrições';


        return view('layouts.aluno.examesAluno', [
            'arrayUC' => $nomesInscricoesAvaliacaoDataRecursos,
            'tipo' => $tipo
        ]);
    }

    public function verCalendario()
    {
        $date = date('Y-m-d');

        //guardar a informacao toda dos semestres
        $linhaSemestres = Semestre::all();

        $semestreInicio = '';
        $semestreFim = '';
        foreach ($linhaSemestres as $semestres) {
            if ($date > $semestres['data_inicio'] &&
                $date < $semestres['data_fim']) {
                $semestreInicio = $semestres['data_inicio'];
                $semestreFim = $semestres['data_fim'];
            }
        }


        return view('pages.aluno.verCalendario', [
            'inputminimoData' => $semestreInicio,
            'inputfimData' => $semestreFim
        ]);
    }

    public function showCalendario(Request $r)
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero aluno
        $numeroAluno = $linhaUtilizador['aluno_id'];

        $date = date('Y-m-d');

        //guardar a informacao toda dos semestres
        $linhaSemestres = Semestre::all();

        $semestreInicio = '';
        $semestreFim = '';
        foreach ($linhaSemestres as $semestres) {
            if ($date > $semestres['data_inicio'] &&
                $date < $semestres['data_fim']) {
                $semestreInicio = $semestres['data_inicio'];
                $semestreFim = $semestres['data_fim'];
            }
        }

        $dateInput = $r->data_avaliacao;

        $yearDateInput = date('Y', strtotime($dateInput));
        $monthDateInput = date('m', strtotime($dateInput));
        $MonthNameYearDateInput = date('F Y', strtotime($dateInput));
        $monthEndDateInput = date('t', strtotime($dateInput));
        $arrayDayNameWeek = array();

        for ($i = 1; $i <= $monthEndDateInput + 1; $i++) {
            $name = date('l', strtotime($yearDateInput . '-' . $monthDateInput . '-' . $i));
            $arrayDayNameWeek[] = $name;
        }

        if ($arrayDayNameWeek[0] == 'Sunday') {
            $numberDay = 0;
        } elseif ($arrayDayNameWeek[0] == 'Monday') {
            $numberDay = 1;
        } elseif ($arrayDayNameWeek[0] == 'Tuesday') {
            $numberDay = 2;
        } elseif ($arrayDayNameWeek[0] == 'Wednesday') {
            $numberDay = 3;
        } elseif ($arrayDayNameWeek[0] == 'Thursday') {
            $numberDay = 4;
        } elseif ($arrayDayNameWeek[0] == 'Friday') {
            $numberDay = 5;
        } elseif ($arrayDayNameWeek[0] == 'Saturday') {
            $numberDay = 6;
        }

        //ver avaliacoes
        $linhasInscricaoAvalicao = Inscricao_Avaliacao::where('aluno_id', '=', $numeroAluno)->get();

        $arraydiaAval = array();
        $arrayidUcAval = array();
        $arraynomeAval = array();
        $arraynomeUcAval = array();

        //guardar avaliacoes dentro do semestre e do mes da data input ja com filtragem
        foreach ($linhasInscricaoAvalicao as $inscricao) {
            $linhaAval = Avaliacao::where('id', '=', $inscricao['avaliacao_id'])->first();
            if ($linhaAval['data_avaliacao'] >= $semestreInicio
                && $linhaAval['data_avaliacao'] <= $semestreFim
                && date('Y', strtotime($linhaAval['data_avaliacao'])) == $yearDateInput
                && date('m', strtotime($linhaAval['data_avaliacao'])) == $monthDateInput) {
                $arraydiaAval[] = date('d', strtotime($linhaAval['data_avaliacao']));
                $arrayidUcAval[] = $linhaAval['uc_id'];
                $arraynomeAval[] = $linhaAval['nome_avaliacao'];
            }
        }

        //ir buscar os nomes das cadeiras das avaliacoes
        foreach ($arrayidUcAval as $idUc) {
            $linhaUc = UC::where('id', '=', $idUc)->first();
            $arraynomeUcAval[] = $linhaUc['nome_uc'];
        }

        return view('layouts.calendarAlunof', [
            'arrayDayNamesWeek' => $arrayDayNameWeek,
            'numberDay' => $numberDay,
            'month_year' => $MonthNameYearDateInput,
            'inputminimoData' => $semestreInicio,
            'inputfimData' => $semestreFim,
            'arraydiaAval' => $arraydiaAval,
            'arraynomeAval' => $arraynomeAval,
            'arraynomeUcAval' => $arraynomeUcAval
        ]);
    }
}
