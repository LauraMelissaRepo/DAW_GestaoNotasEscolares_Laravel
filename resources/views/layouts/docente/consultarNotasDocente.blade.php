@extends('layouts.docente.escolherCadeiraConsultarNotas')
@section('listaAlunosConsultar')
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
                    <h6>Não tem nota</h6>
                @else
                    <h6>{{$arraynotasAlunos[$i]}}</h6>
                @endif
            </div>
        </div>
    @endfor
@endsection
