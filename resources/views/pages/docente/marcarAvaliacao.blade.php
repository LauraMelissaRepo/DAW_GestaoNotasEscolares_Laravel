@extends('layouts.docente.navbarDocente')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <form method="post" action="{{route('get_date_uc')}}">
                    @csrf
                    <select name="filterCadeira" id="cadeira">
                        <option value="nothing">Escolha uma Cadeira</option>
                        @foreach($arrayCadeirasLeciona as $cadeiraLeciona)
                            @if($cadeiraLeciona == $placeHolderCadeira)
                                <option selected value="{{$cadeiraLeciona}}">{{$cadeiraLeciona}}</option>
                            @else
                                <option value="{{$cadeiraLeciona}}">{{$cadeiraLeciona}}</option>
                            @endif
                        @endforeach
                    </select>
                    @if($placeHolderData != '')
                        <input class="ml-4" type="date" name="data_avaliacao" value="{{$placeHolderData}}"
                               min="{{$inputminimoData}}" max="{{$inputfimData}}">
                    @else
                        <input class="ml-4" type="date" name="data_avaliacao" value="{{date('Y-m-d')}}"
                               min="{{$inputminimoData}}" max="{{$inputfimData}}">
                    @endif
                    <input class="ml-4" type="submit" value="Confirmar Avaliação">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @if(session('faltaCadeira'))
                    <div class="alert alert-danger alert-block mt-3">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{session('faltaCadeira')}}</strong>
                    </div>
                @endif
                @if(session('faltaDescricao'))
                    <div class="alert alert-danger alert-block mt-3">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{session('faltaDescricao')}}</strong>
                    </div>
                @endif
            </div>
        </div>
        @yield('calendar')
    </div>
@endsection
