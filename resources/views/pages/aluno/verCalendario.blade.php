@extends('layouts.aluno.navbarAluno')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <form method="post" action="{{route('get_date_consulta_calendario')}}">
                    @csrf
                    <input class="ml-4" type="date" name="data_avaliacao" value="{{date('Y-m-d')}}" min="{{$inputminimoData}}" max="{{$inputfimData}}">
                    <input class="ml-4" type="submit" value="Confirmar Data">
                </form>
            </div>
        </div>
        @yield('calendarAluno')
    </div>
@endsection
{{--Tem que se ir buscar o calendário e pôr-se aqui--}}
