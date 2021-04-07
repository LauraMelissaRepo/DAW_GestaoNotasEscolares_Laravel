@extends('layouts.docente.navbarDocente')
@section('content')
    <div class="container">
        <form method="post" action="{{route('get_items_Choose_Chair_Consultar')}}">
            @csrf
            <div class="row">
                <div class="col">
                    <select class="form-control form-control-sm" name="cadeiraLancarNotas">
                        <option disabled selected name="nothing">Cadeira</option>
                        @foreach($arrayCadeirasLeciona as $cadeiraLeciona)
                            @if($placeHolderCadeira == $cadeiraLeciona)
                                <option selected name="{{$placeHolderCadeira}}">{{$placeHolderCadeira}}</option>
                            @else
                                <option name="{{$cadeiraLeciona}}">{{$cadeiraLeciona}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <select class="form-control form-control-sm" name="epocaLancarNotas">
                        <option disabled selected name="epocaLancarNotas">Época</option>
                        @if($placeHolderEpoca == 'Normal')
                            <option selected name="epocaNormalLancarNotas">Normal</option>
                        @else
                            <option name="epocaNormalLancarNotas">Normal</option>
                        @endif
                        @if($placeHolderEpoca == 'Recurso')
                            <option selected name="epocaRecursoLancarNotas">Recurso</option>
                        @else
                            <option name="epocaRecursoLancarNotas">Recurso</option>
                        @endif
                    </select>
                </div>
                <input type="submit" class="btn btn-light btn-outline-dark ml-5" value="Validar Escolhas">
            </div>
        </form>
            @if(session('faltaCadeiraEpoca'))
                <div class="alert alert-danger alert-block mt-3">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{session('faltaCadeiraEpoca')}}</strong>
                </div>
            @endif    @if(session('faltaAvaliacao'))
                <div class="alert alert-danger alert-block mt-3">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{session('faltaAvaliacao')}}</strong>
                </div>
            @endif
    </div>
    <div class="container">
        @yield('escolhaCadeiraConsultar')
    </div>
@endsection
