@extends('pages.aluno.consultarNotas')
@section('escolherCadeira')
    <div class="container">
        <form method="post" action="{{route('get_listConsultarNotasAlunofilter')}}">
            @csrf
            <div class="row mt-3">
                <div class="col">
                    <select class="form-control form-control-sm" name="cadeiraConsultarNotaAluno">
                        <option name="nothing">Cadeira</option>
                        @for($i = 0; $i < count($arrayLinhasUcsFiltradas); $i++)
                            @if($placeHolderCadeira == $arrayLinhasUcsFiltradas[$i])
                                <option selected
                                        name="{{$arrayLinhasUcsFiltradas[$i]}}">{{$arrayLinhasUcsFiltradas[$i]}}</option>
                            @else
                                <option name="{{$arrayLinhasUcsFiltradas[$i]}}">{{$arrayLinhasUcsFiltradas[$i]}}</option>
                            @endif
                        @endfor
                    </select>
                </div>
                <div>
                    <input type="submit" value="Mostrar Notas">
                    <input type="hidden" name="anoConsultarNotaAluno" value="{{$inputIdStringAno}}">
                    <input type="hidden" name="semestreConsultarNotaAluno" value="{{$inputStringSemestre}}">
                </div>
            </div>
        </form>
    </div>
    @yield('consultarNotasAluno')
@endsection
