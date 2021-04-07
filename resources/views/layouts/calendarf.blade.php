@extends('pages.docente.marcarAvaliacao')
@section('calendar')
    <form method="post" action="{{route('insertAvalTable')}}">
        @csrf
        <div class="row mt-3 ml-5">
            <div class="col">
                <table class="table-bordered table-striped mt-3">
                    <h1 class="">{{date('d F Y', strtotime($dateInput))}}</h1>
                    @for($j = 0; $j < $numberDay; $j++)
                        <td style="background-color: #F0FFFF; border-color: #1b1e21"><br></td>
                    @endfor
                    @for($i = 0; $i < count($arrayDayNamesWeek); $i++)
                        <td style="background-color: #FFFAF0; border-color: #1b1e21">
                            <b>{{$i+1}}</b><br>{{$arrayDayNamesWeek[$i]}}</td>
                        @if($arrayDayNamesWeek[$i] == 'Saturday')
                            <tr></tr>
                        @endif
                    @endfor
                </table>
            </div>
            <div class="col">
                <h1 class="ml-5">Descrição para a avaliação da UC: {{$ucChoosen}}</h1>
                <textarea class="ml-5 mt-3" name="descricao_avalicao" rows="8" cols="50"></textarea>
            </div>
            <input type="hidden" name="dateinputhidden" value="{{$dateInput}}">
            <input type="hidden" name="docenteidhidden" value="{{$idDocente}}">
            <input type="hidden" name="ucchoosenhidden" value="{{$ucChoosen}}">
        </div>
        <div class="row">
            <div class="col">
                @if(session('faltaDescricao'))
                    <div class="alert alert-danger alert-block mt-3">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{session('faltaDescricao')}}</strong>
                    </div>
                @endif
            </div>
        </div>
        <input type="submit" name="submit" value="Submeter Avalição" class="mt-3 ml-5 btn btn-info">
    </form>
@endsection
