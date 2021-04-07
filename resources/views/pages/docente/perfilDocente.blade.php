@extends('layouts.docente.navbarDocente')

@section('content')
    <table>
        <tr>
            <td>
                <h1 style="font-weight: bold">Perfil</h1>
            </td>
        </tr>
        <tr>
            <td>
                <h4 class="mt-3">Nome: {{$namehtml}}</h4>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Número: {{$numerohtml}}</h4>
            </td>
        </tr>
    </table>
@endsection
