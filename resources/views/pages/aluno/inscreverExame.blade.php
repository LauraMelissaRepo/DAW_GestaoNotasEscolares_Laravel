@extends('layouts.aluno.navbarAluno')
@section('content')
    <div class="container ">
        <div class="row mt-3">
            <div class="col">
                <form method="get" action="{{route('get_recursos')}}">
                    @csrf
                    <input type="submit" value="Inscrever a Recurso">
                </form>
            </div>
            <div class="col">
                <form method="get" action="{{route('get_melhorias')}}">
                    @csrf
                    <input type="submit" value="Inscrever a Melhoria">
                </form>
            </div>
            <div class="col">
                <form method="get" action="{{route('get_todos')}}">
                    @csrf
                    <input type="submit" value="Ver Inscrições">
                </form>
            </div>
        </div>
    </div>
    @yield('exames')
@endsection
