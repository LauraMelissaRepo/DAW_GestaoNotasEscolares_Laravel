@extends('layouts.docente.escolherCadeiraLancarNotas')
@section('listaAlunosLancar')
    <form method="post" action="{{route('insert_Notas_table')}}">
        @csrf
        <div class="row">
            <div class="col mt-3">
                <h3 style="font-weight: bold">Alunos</h3>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <h4>Nome</h4>
            </div>
            <div class="col">
                <h4>Número</h4>
            </div>
            <div class="col">
                <h4>Nota</h4>
            </div>
        </div>
        @for($i = 0; $i < count($arraynumeroAlunos); $i ++)
            <div class="row mt-2">
                <div class="col">
                    <h6>{{$arraynomesAlunos[$i]}}</h6>
                </div>
                <div class="col">
                    <h6>{{$arraynumeroAlunos[$i]}}</h6>
                </div>
                <div class="col">
                    @if($arraynotasAlunos[$i] == null)
                        <input type="number" name="{{$arrayidsnotasAlunos[$i]}}" min="0" max="20" step=".01" value="">
                    @else
                        <input type="number" name="{{$arrayidsnotasAlunos[$i]}}" min="0" max="20" step=".01" value="{{$arraynotasAlunos[$i]}}">
                    @endif
                </div>
            </div>
        @endfor
        <div class="row">
            <div class="col mt-3">
                <input type="submit" name="submit" value="Submeter Notas" class="mt-5" style="background-color: #2a9055">
            </div>
        </div>
        <input type="hidden" name="idEpocaInsert" value="{{$idEpocaInsert}}">
        <input type="hidden" name="idUcInsert" value="{{$idUcInsert}}">
        <input type="hidden" name="nomeAvaliacaoInsert" value="{{$nomeAvaliacaoInsert}}">
    </form>
@endsection
