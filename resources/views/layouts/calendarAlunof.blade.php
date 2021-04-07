@extends('pages.aluno.verCalendario')
@section('calendarAluno')
    <div class="row mt-3 ml-5">
        <div class="col">
            <table class="table-bordered table-striped mt-3">
                <h1 class="">{{$month_year}}</h1>
                @for($j = -1; $j < $numberDay; $j++)
                    <td style="background-color: #F0FFFF; border-color: #1b1e21"><br></td>
                @endfor
                @for($i = 1; $i < count($arrayDayNamesWeek); $i++)
                    @if(in_array($i, $arraydiaAval))
                        <td style="background-color: #B8860B; border-color: #1b1e21">
                            <b>{{$i}}</b><br>{{$arrayDayNamesWeek[$i-1]}}</td>
                    @else
                        <td style="background-color: #FFFAF0; border-color: #1b1e21">
                            <b>{{$i}}</b><br>{{$arrayDayNamesWeek[$i-1]}}</td>
                    @endif
                    @if($arrayDayNamesWeek[$i] == 'Saturday')
                        <tr></tr>
                    @endif
                @endfor
            </table>
        </div>
        <div class="col">
            <h1 class="ml">Lista</h1>
            @for($j = 0; $j < count($arraydiaAval); $j++)
                <h4>{{$arraydiaAval[$j]}} {{$month_year}} -> {{$arraynomeUcAval[$j]}}({{$arraynomeAval[$j]}})</h4>
            @endfor
        </div>
    </div>
@endsection
