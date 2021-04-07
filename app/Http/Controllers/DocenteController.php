<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
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

class DocenteController extends Controller
{

    function __construct(){
        //Middlewares para verificar se o login está feito e se o utilizador pode estar na página onde quer ir
        $this->middleware('auth');
        $this->middleware('verificarUser');
    }

    public function perfilDocente()
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //nome docente
        $nomeUser = $linhaUtilizador['name'];
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];

        return view('pages.docente.perfilDocente', [
            'namehtml' => $nomeUser,
            'numerohtml' => $numeroDocente
        ]);
    }

    public function marcarAvaliacao()
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);

        //variaveis para o limite da data no html
        $semestreinicio = '';
        $semestrefim = '';

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];
                //guardar os limites do semestre para mostrar no input date como max e min
                $semestreinicio = $linhaSemestre['data_inicio'];
                $semestrefim = $linhaSemestre['data_fim'];
            }
        }

        //place holders para a cadeira e a data
        $placeHolderCadeira = '';
        $placeHolderData = '';

        return view('pages.docente.marcarAvaliacao', [
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'inputminimoData' => $semestreinicio,
            'inputfimData' => $semestrefim,
            'placeHolderCadeira' => $placeHolderCadeira,
            'placeHolderData' => $placeHolderData
        ]);
    }

    public function lancarNotas()
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);
        $placeHolderCadeira = '';
        $placeHolderEpoca = '';

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];

            }
        }

        return view('pages.docente.lancarNotas', [
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'placeHolderCadeira' => $placeHolderCadeira,
            'placeHolderEpoca' => $placeHolderEpoca
        ]);
    }

    public function showChairLancar(Request  $r){
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];

            }
        }

        //receber as variáveis de input da escolha da cadeira e da epoca de recurso
        $cadeiraEscolhida = $r->cadeiraLancarNotas;
        $linhaUc = UC::where('nome_uc', '=', $cadeiraEscolhida )->first();
        $idUc = $linhaUc['id'];
        $epocaEscolhida = $r->epocaLancarNotas;

        //filtrar as avaliaçoes existentes com base na cadeira e na epoca

        if($epocaEscolhida == 'Normal'){
            $idEpoca = 1;
        }else{
            $idEpoca = 2;
        }

        $idAvaliacoes = array();
        $nomeAvaliacoes = array();
        $linhasAvaliacoes = Avaliacao::where([['epoca', '=', $idEpoca], ['uc_id', '=', $idUc]])->get();
        foreach ($linhasAvaliacoes as $avaliacao){
            $idAvaliacoes[] = $avaliacao['id'];
            $nomeAvaliacoes[] = $avaliacao['nome_avaliacao'];
        }

        return view('layouts.docente.escolherCadeiraLancarNotas',[
            'arrayIdAvaliacaoes' => $idAvaliacoes,
            'arrayNomeAvaliacaoes' => $nomeAvaliacoes,
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'placeHolderCadeira' => $cadeiraEscolhida,
            'placeHolderEpoca' => $epocaEscolhida,
            'placeHolderAvaliacao' => ''
        ]);
    }

    public function showCalUC(Request $r)
    {
        //variaveis de input da funcao marcarAvaliacao para recarregar a pagina quando se chama a section
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);

        //variaveis para o limite da data no html
        $semestreinicio = '';
        $semestrefim = '';

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];
                //guardar os limites do semestre para mostrar no input date como max e min
                $semestreinicio = $linhaSemestre['data_inicio'];
                $semestrefim = $linhaSemestre['data_fim'];
            }
        }


        $dateInput = $r->data_avaliacao;
        $ucChoosen = $r->filterCadeira;

        $yearDateInput = date('Y', strtotime($dateInput));
        $monthDateInput = date('m', strtotime($dateInput));
        $monthEndDateInput = date('t', strtotime($dateInput));
        $arrayDayNameWeek = array();

        for ($i = 1; $i <= $monthEndDateInput; $i++) {
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

        //placeHolders
        $placeHolderCadeira = $ucChoosen;
        $placeHolderData = $dateInput;

        if ($ucChoosen == 'nothing') {
            return view('error.404');
        } else {
            return view('layouts.calendarf', [
                'arrayDayNamesWeek' => $arrayDayNameWeek,
                'idDocente' => $numeroDocente,
                'numberDay' => $numberDay,
                'ucChoosen' => $ucChoosen,
                'dateInput' => $dateInput,
                'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
                'inputminimoData' => $semestreinicio,
                'inputfimData' => $semestrefim,
                'placeHolderCadeira' => $placeHolderCadeira,
                'placeHolderData' => $placeHolderData
            ]);
        }
    }

    public function dbAvalInsert(Request $r)
    {
        //codigo repetido para recarregar a pagina
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);

        //variaveis para o limite da data no html
        $semestreinicio = '';
        $semestrefim = '';

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];
                //guardar os limites do semestre para mostrar no input date como max e min
                $semestreinicio = $linhaSemestre['data_inicio'];
                $semestrefim = $linhaSemestre['data_fim'];
            }
        }

        //place holders para a cadeira e a data
        $placeHolderCadeira = '';
        $placeHolderData = '';

        //data para insert
        $dateTesteInput = $r->dateinputhidden;

        //descricao avaliacao insert
        $nomeAval = $r->descricao_avalicao;

        //id uc avaliacao insert
        $ucChoosen = $r->ucchoosenhidden;
        $linhaUcAval = UC::where('nome_uc', '=', $ucChoosen)->first();
        $idUcChoosen = $linhaUcAval['id'];

        //epoca avaliacao insert
        $linhaUc = UC::where('id', '=', $idUcChoosen)->first();
        $idSemestre = $linhaUc['semestre_id'];
        $linhaSemestre = Semestre::where('id', '=', $idSemestre)->first();
        $inicioSemestre = $linhaSemestre['data_inicio'];
        $fimSemestre = $linhaSemestre['data_fim'];
        $separacaoEpocas = date('Y-m-d', strtotime('-15 days', strtotime($fimSemestre)));
        if ($dateTesteInput > $inicioSemestre
            && $dateTesteInput < $separacaoEpocas) {
            $epoca = 1;
        } else {
            $epoca = 2;
        }

        //id docente avaliacao insert
        $idDocente = $r->docenteidhidden;

//        dd($dateTesteInput,$nomeAval, $epoca, $idDocente, $idUcChoosen);

        //insert da avaliacao na tabela
        $insert = new Avaliacao;
        $insert->data_avaliacao = $dateTesteInput;
        $insert->nome_avaliacao = $nomeAval;
        $insert->epoca = $epoca;
        $insert->docente_id = $idDocente;
        $insert->uc_id = $idUcChoosen;
        $insert->save();

        //inscricao de todos os alunos que tem essa cadeira em epoca normal
        if ($epoca == 1) {
            //ir buscar todas as linhas da tabela onde o id da uc da avaliacao, corresponde ao id_uc das inscricoes feitas à uc em funcionamento
            $linhasUcFuncionamento = UC_Funcionamento::all()->where('uc_id', '=', $idUcChoosen);
            foreach ($linhasUcFuncionamento as $linha) {
                $linhaInscricaoMatricula[] = Inscricao_Matricula::where('id', '=', $linha['incricaoMatricula_id'])->first();
                $linhaInscricaoMatricula = $linhaInscricaoMatricula[0];
                $alunosInscritosCadeira[] = $linhaInscricaoMatricula['aluno_id'];
            }

            //ir buscar todos os ultimos ids inseridos para os inserts baterem certo mais tarde com um counter
            $idLastClass = Classificacao::all()->last();
            $idLastInsc = Inscricao_Avaliacao::all()->last();
            $idLastAval = Avaliacao::all()->last();
            $counter = 0;
            foreach ($alunosInscritosCadeira as $aluno) {
                $counter++;

                //insert na table Classificacao para esta estar associada à avaliacao marcada
                $insertTClassi = new Classificacao;
                $insertTClassi->valor_classificacao = null;
                $insertTClassi->data_lancamento = null;
                $insertTClassi->incricao_avaliacao_id = $idLastInsc['id'] + $counter;
                $insertTClassi->save();

                //insert automatico caso seja epoca normal na table Inscricao_Avaliacao para todos os alunos inscritos à cadeira onde a avaliacao foi marcada
                $insertTInsAva = new Inscricao_Avaliacao;
                $insertTInsAva->data = date('Y-m-d');
                $insertTInsAva->avaliacao_id = $idLastAval['id'];
                $insertTInsAva->aluno_id = $aluno;
                $insertTInsAva->classificacao_id = $idLastClass['id'] + $counter;
                $insertTInsAva->save();
            }
        }

        return view('pages.docente.marcarAvaliacao', [
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'inputminimoData' => $semestreinicio,
            'inputfimData' => $semestrefim,
            'placeHolderCadeira' => $placeHolderCadeira,
            'placeHolderData' => $placeHolderData
        ]);
    }

    public function mostrarListaNotasMarcar(Request $r)
    {
        //codigo para nao dar erro nos ficheiros blade a cima feitos
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];

            }
        }

        //receber as variáveis de input da escolha da cadeira e da epoca de recurso
        $cadeiraEscolhida = $r->cadeiraLancarNotas;
        $linhaUc = UC::where('nome_uc', '=', $cadeiraEscolhida )->first();
        $idUc = $linhaUc['id'];
        $epocaEscolhida = $r->epocaLancarNotas;

        //filtrar as avaliaçoes existentes com base na cadeira e na epoca

        if($epocaEscolhida == 'Normal'){
            $idEpoca = 1;
        }else{
            $idEpoca = 2;
        }

        $linhasAvaliacoes = Avaliacao::where([['epoca', '=', $idEpoca], ['uc_id', '=', $idUc]])->get();
        foreach ($linhasAvaliacoes as $avaliacao){
            $idAvaliacoes[] = $avaliacao['id'];
            $nomeAvaliacoes[] = $avaliacao['nome_avaliacao'];
        }

        //placeHolder para verificar se a avaliacao já foi escolhida
        $placeHolderAvaliacao = $r->avaliacao;

        //buscar valores dos alunos à BD de acordo com os inputs inseridos
        //receber o id da avaliacao escolhida
        $linhaAvaliacaoInput = Avaliacao::where([['epoca', '=', $idEpoca],['uc_id', '=', $idUc], ['nome_avaliacao', '=', $placeHolderAvaliacao]])->first();
        $idAvaliacao = $linhaAvaliacaoInput['id'];
        //pegar todas as inscriçoes a essa avaliacao
        $linhasInscricoes = Inscricao_Avaliacao::where('avaliacao_id', '=', $idAvaliacao)->get();
        $nomesAlunos = array();
        $numeroAlunos = array();
        $idsnotasAluno = array();
        $notasAlunos = array();
        foreach ($linhasInscricoes as $inscricao){
            $idAluno = $inscricao['aluno_id'];
            $linhaAluno = Aluno::where('id', '=', $idAluno)->first();
            $nomesAlunos[] = $linhaAluno['nome'];
            $numeroAlunos[] = $idAluno;
            $linhaClassificacao = Classificacao::where('id', '=', $inscricao['classificacao_id'])->first();
            $idsnotasAluno[] = $inscricao['classificacao_id'];
            $notasAlunos[] = $linhaClassificacao['valor_classificacao'];
        }

        return view('layouts.docente.lancarNotas',[
            'arrayIdAvaliacaoes' => $idAvaliacoes,
            'arrayNomeAvaliacaoes' => $nomeAvaliacoes,
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'placeHolderCadeira' => $cadeiraEscolhida,
            'placeHolderEpoca' => $epocaEscolhida,
            'placeHolderAvaliacao' => $placeHolderAvaliacao,
            'arraynomesAlunos' => $nomesAlunos,
            'arraynumeroAlunos' => $numeroAlunos,
            'arraynotasAlunos' => $notasAlunos,
            'arrayidsnotasAlunos' => $idsnotasAluno,
            'idEpocaInsert' => $idEpoca,
            'idUcInsert' => $idUc,
            'nomeAvaliacaoInsert' => $placeHolderAvaliacao
        ]);
    }

    public function insertNotasTable(Request $r){
        //codigo copiado para recarregar o inicio da pagina
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);
        $placeHolderCadeira = '';
        $placeHolderEpoca = '';

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];

            }
        }

        //buscar as variaveis para realizar a consulta dos ids das classificacoes
        $idEpoca = $r->idEpocaInsert;
        $idUc = $r->idUcInsert;
        $placeHolderAvaliacao = $r->nomeAvaliacaoInsert;

        $linhaAvaliacaoInput = Avaliacao::where([['epoca', '=', $idEpoca],['uc_id', '=', $idUc], ['nome_avaliacao', '=', $placeHolderAvaliacao]])->first();
        $idAvaliacao = $linhaAvaliacaoInput['id'];
        //pegar todas as inscriçoes a essa avaliacao
        $linhasInscricoes = Inscricao_Avaliacao::where('avaliacao_id', '=', $idAvaliacao)->get();
        foreach ($linhasInscricoes as $inscricao){
            $idsnotasAluno[] = $inscricao['classificacao_id'];
        }
        foreach ($idsnotasAluno as $idnota){
            $inputNota = $r->$idnota;
            $classificacaoInsert = Classificacao::find($idnota);
            $classificacaoInsert-> valor_classificacao = $inputNota;
            $classificacaoInsert-> data_lancamento = date('Y-m-d');
            $classificacaoInsert->save();
        }

        return view('pages.docente.lancarNotas', [
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'placeHolderCadeira' => $placeHolderCadeira,
            'placeHolderEpoca' => $placeHolderEpoca
        ]);
    }

    public function consultarNotas(){
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);
        $placeHolderCadeira = '';
        $placeHolderEpoca = '';

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];

            }
        }

        return view('pages.docente.consultarNotasDocente', [
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'placeHolderCadeira' => $placeHolderCadeira,
            'placeHolderEpoca' => $placeHolderEpoca
        ]);
    }

    public function showChairConsultar(Request  $r)
    {
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];

            }
        }

        //receber as variáveis de input da escolha da cadeira e da epoca de recurso
        $cadeiraEscolhida = $r->cadeiraLancarNotas;
        $linhaUc = UC::where('nome_uc', '=', $cadeiraEscolhida)->first();
        $idUc = $linhaUc['id'];
        $epocaEscolhida = $r->epocaLancarNotas;

        //filtrar as avaliaçoes existentes com base na cadeira e na epoca

        if ($epocaEscolhida == 'Normal') {
            $idEpoca = 1;
        } else {
            $idEpoca = 2;
        }

        $linhasAvaliacoes = Avaliacao::where([['epoca', '=', $idEpoca], ['uc_id', '=', $idUc]])->get();
        foreach ($linhasAvaliacoes as $avaliacao) {
            $idAvaliacoes[] = $avaliacao['id'];
            $nomeAvaliacoes[] = $avaliacao['nome_avaliacao'];
        }

        return view('layouts.docente.escolherCadeiraConsultarNotas', [
            'arrayIdAvaliacaoes' => $idAvaliacoes,
            'arrayNomeAvaliacaoes' => $nomeAvaliacoes,
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'placeHolderCadeira' => $cadeiraEscolhida,
            'placeHolderEpoca' => $epocaEscolhida,
            'placeHolderAvaliacao' => ''
        ]);
    }

    public function mostrarListaNotasConsultar(Request $r)
    {
        //codigo para nao dar erro nos ficheiros blade a cima feitos
        $user = Auth::user();
        $userID = $user['id'];
        $linhaUtilizador = User::where('id', '=', $userID)->first();
        //numero docente
        $numeroDocente = $linhaUtilizador['docente_id'];
        //cadeiras que leciona
        $linhasUCleciona = UC::all()->where('docente_id', $numeroDocente);

        //for para eliminar ucs que não estejam em funcionamento
        $dataAtual = date('Y-m-d');
        $cadeirasLecionaFuncionamento = array();
        foreach ($linhasUCleciona as $uc) {
            //ir buscar o semestre
            $linhaSemestre = Semestre::where('id', '=', $uc['semestre_id'])->first();
            //ir buscar o ano letivo
            $linhaUcfuncionamento = UC_Funcionamento::where('uc_id', '=', $uc['id'])->first();
            $linhaAnoletivo = AnoLetivo::where('id', '=', $linhaUcfuncionamento['anoletivo_id'])->first();
            //filtragem das ucs que estao em funcionamento neste momento
            if ($dataAtual > $linhaSemestre['data_inicio']
                && $dataAtual < $linhaSemestre['data_fim']
                && $dataAtual > $linhaAnoletivo['anoletivo_inicio']
                && $dataAtual < $linhaAnoletivo['anoletivo_fim']) {
                //caso a data atual esteja dentro dos limites do semestre/ano letivo associado à cadeira,
                // esta é guardada no array
                $cadeirasLecionaFuncionamento[] = $uc['nome_uc'];

            }
        }

        //receber as variáveis de input da escolha da cadeira e da epoca de recurso
        $cadeiraEscolhida = $r->cadeiraLancarNotas;
        $linhaUc = UC::where('nome_uc', '=', $cadeiraEscolhida )->first();
        $idUc = $linhaUc['id'];
        $epocaEscolhida = $r->epocaLancarNotas;

        //filtrar as avaliaçoes existentes com base na cadeira e na epoca

        if($epocaEscolhida == 'Normal'){
            $idEpoca = 1;
        }else{
            $idEpoca = 2;
        }

        $linhasAvaliacoes = Avaliacao::where([['epoca', '=', $idEpoca], ['uc_id', '=', $idUc]])->get();
        foreach ($linhasAvaliacoes as $avaliacao){
            $idAvaliacoes[] = $avaliacao['id'];
            $nomeAvaliacoes[] = $avaliacao['nome_avaliacao'];
        }

        //placeHolder para verificar se a avaliacao já foi escolhida
        $placeHolderAvaliacao = $r->avaliacao;

        //buscar valores dos alunos à BD de acordo com os inputs inseridos
        //receber o id da avaliacao escolhida
        $linhaAvaliacaoInput = Avaliacao::where([['epoca', '=', $idEpoca],['uc_id', '=', $idUc], ['nome_avaliacao', '=', $placeHolderAvaliacao]])->first();
        $idAvaliacao = $linhaAvaliacaoInput['id'];
        //pegar todas as inscriçoes a essa avaliacao
        $linhasInscricoes = Inscricao_Avaliacao::where('avaliacao_id', '=', $idAvaliacao)->get();
        $nomesAlunos = array();
        $numeroAlunos = array();
        $idsnotasAluno = array();
        $notasAlunos = array();
        foreach ($linhasInscricoes as $inscricao){
            $idAluno = $inscricao['aluno_id'];
            $linhaAluno = Aluno::where('id', '=', $idAluno)->first();
            $nomesAlunos[] = $linhaAluno['nome'];
            $numeroAlunos[] = $idAluno;
            $linhaClassificacao = Classificacao::where('id', '=', $inscricao['classificacao_id'])->first();
            $idsnotasAluno[] = $inscricao['classificacao_id'];
            $notasAlunos[] = $linhaClassificacao['valor_classificacao'];
        }

        return view('layouts.docente.consultarNotasDocente',[
            'arraynumeroAlunos' => $numeroAlunos,
            'arraynomesAlunos' => $nomesAlunos,
            'arraynotasAlunos' => $notasAlunos,
            'arrayIdAvaliacaoes' => $idAvaliacoes,
            'arrayNomeAvaliacaoes' => $nomeAvaliacoes,
            'arrayCadeirasLeciona' => $cadeirasLecionaFuncionamento,
            'placeHolderCadeira' => $cadeiraEscolhida,
            'placeHolderEpoca' => $epocaEscolhida,
            'placeHolderAvaliacao' => $placeHolderAvaliacao,
            'arrayidsnotasAlunos' => $idsnotasAluno,
            'idEpocaInsert' => $idEpoca,
            'idUcInsert' => $idUc,
            'nomeAvaliacaoInsert' => $placeHolderAvaliacao
        ]);
    }

}
