@extends('layouts.aluno.navbarAluno')
@section('content')
    <div class="container">
        <form method="post" action="{{route('get_listConsultarNotasAluno')}}">
            @csrf
            <div class="row mt-3">
                <div class="col">
                    <input type="submit" name="submit" value="Mostrar Todas as notas">
                </div>
            </div>
        </form>
        <form method="post" action="{{route('get_Cadeira_Aluno_ConsultarNotas')}}">
            @csrf
            <div class="row mt-3">
                <div class="col">
                    <select class="form-control form-control-sm" name="anoConsultarNotaAluno">
                        <option name="nothing">Ano</option>
                        @for($i = 0; $i < count($arrayIdsAnosLetivos); $i++)
                            @if($placeHolderAnoLetivo == $arrayStringAnosLetivos[$i])
                                <option selected
                                        name="{{$arrayIdsAnosLetivos[$i]}}">{{$arrayStringAnosLetivos[$i]}}</option>
                            @else
                                <option name="{{$arrayIdsAnosLetivos[$i]}}">{{$arrayStringAnosLetivos[$i]}}</option>
                            @endif
                        @endfor
                    </select>
                </div>
                <div class="col">
                    <select class="form-control form-control-sm" name="semestreConsultarNotaAluno">
                        <option name="nothing">Semestre</option>
                        @if($placeHolderSemestre == '1º Semestre')
                            <option selected name="1Semestre">1º Semestre</option>
                        @else
                            <option name="1Semestre">1º Semestre</option>
                        @endif
                        @if($placeHolderSemestre == '2º Semestre')
                            <option selected name="2Semestre">2º Semestre</option>
                        @else()
                            <option name="2Semestre">2º Semestre</option>
                        @endif
                    </select>
                </div>
                <div class="col">
                    <input type="submit" name="submit" value="Validar Escolhas">
                </div>
            </div>
        </form>
        @if(session('faltaAnoSemestre'))
            <div class="alert alert-danger alert-block mt-3">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{session('faltaAnoSemestre')}}</strong>
            </div>
        @endif    @if(session('faltaCadeira'))
            <div class="alert alert-danger alert-block mt-3">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{session('faltaCadeira')}}</strong>
            </div>
        @endif
        <div class="row">
            @yield('escolherCadeira')
        </div>
        <div class="row">
            @yield('consultarTodasNotasAluno')
        </div>
    </div>
@endsection
