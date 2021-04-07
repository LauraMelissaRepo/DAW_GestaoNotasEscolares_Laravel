@extends('layouts.aluno.mostrarCadeiraAlunoConsultarNotas')
@section('consultarNotasAluno')
    <div class="container">
        <div class="row mt-3">
            <div class="col">
                <h2><b>Notas:</b></h2>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <h4><b>Avaliação</b></h4>
            </div>
            <div class="col">
                <h4><b>Nota</b></h4>
            </div>
            <div class="col">
                <h4><b>Data Lançamento</b></h4>
            </div>
            <div class="col">
                <h4><b>Época</b></h4>
            </div>
            <div class="col">
                <h4><b>Estado</b></h4>
            </div>
        </div>
    </div>
    @for($i = 0; $i < count($arrayNomeAval); $i++)
        <div class="container">
            <div class="row mt-3">
                <div class="col">
                    <h6>{{$arrayNomeAval[$i]}}</h6>
                </div>
                <div class="col">
                    <h6>{{$arrayNotaAval[$i]}}</h6>
                </div>
                <div class="col">
                    <h6>{{$arrayDataLancaAval[$i]}}</h6>
                </div>
                <div class="col">
                    <h6>{{$arrayEpocaAval[$i]}}</h6>
                </div>
                <div class="col">
                    <h6>{{$arrayEstadoAval[$i]}}</h6>
                </div>
            </div>
        </div>
    @endfor
@endsection
