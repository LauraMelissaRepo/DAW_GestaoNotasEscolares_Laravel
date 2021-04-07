@extends('pages.docente.consultarNotasDocente')
@section('escolhaCadeiraConsultar')
    <form method="post" action="{{route('get_listConsultarNotas')}}">
        @csrf
        <div class="row mt-3">
            <div class="col">
                <select class="form-control form-control-sm" name="avaliacao">
                    <option name="avaliacao">Avaliacao</option>
                    @foreach($arrayNomeAvaliacaoes as $nomeAvaliacao)
                        @if($placeHolderAvaliacao == $nomeAvaliacao)
                            <option selected name="{{$nomeAvaliacao}}">{{$nomeAvaliacao}}</option>
                        @else
                            <option name="{{$nomeAvaliacao}}">{{$nomeAvaliacao}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="submit" class="btn btn-light btn-outline-dark ml-5" value="Mostrar Lista">
                <input type="hidden" name="cadeiraLancarNotas" value="{{$placeHolderCadeira}}">
                <input type="hidden" name="epocaLancarNotas" value="{{$placeHolderEpoca}}">
            </div>
        </div>
    </form>
{{--    @if(session('faltaAvaliacao'))--}}
{{--        <div class="alert alert-danger alert-block mt-3">--}}
{{--            <button type="button" class="close" data-dismiss="alert">×</button>--}}
{{--            <strong>{{session('faltaAvaliacao')}}</strong>--}}
{{--        </div>--}}
{{--    @endif--}}
    <div class="container">
        @yield('listaAlunosConsultar')
    </div>
@endsection
