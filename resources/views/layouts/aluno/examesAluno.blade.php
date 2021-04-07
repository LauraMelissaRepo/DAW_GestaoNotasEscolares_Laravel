@extends('pages.aluno.inscreverExame')
@section('exames')
    <div class="container">
        <form method="post" action="{{route('insert_recurso_melhoria')}}">
            @csrf
            <h2 class="mt-3">Lista {{$tipo}}:</h2>
            @if($tipo == 'Inscrições')
                @foreach($arrayUC as $uc)
                    <div class="row mt-3">
                        <h4 class="ml-3">{{$uc}}</h4>
                    </div>
                @endforeach
            @else
                @foreach($arrayUC as $uc)
                    <div class="row mt-3">
                        <input class="mt-1 ml-3" type="checkbox" value="{{$uc}}" name="uc[]" id="uc">
                        <h4 class="ml-3">{{$uc}}</h4>
                    </div>
                    <div class="row mt-3">
                        <input class="mt-4 btn btn-info" type="submit" value="Submeter Inscrições">
                    </div>
                @endforeach
            @endif
            @if($tipo == 'Inscrições' && count($arrayUC) == 0)
                <h4>Não tem {{$tipo}} feitas neste Ano Letivo.</h4>
            @endif
            @if($tipo != 'Inscrições' && count($arrayUC) == 0)
                <h4>Não tem {{$tipo}} para se inscrever ou já está inscrito.</h4>
            @endif
        </form>
    </div>
@endsection
