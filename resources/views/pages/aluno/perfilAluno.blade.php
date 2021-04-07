@extends('layouts.aluno.navbarAluno')

@section('content')
<table>
    <tr>
        <td>
            <h1>Perfil</h1>
        </td>
    </tr>
    <tr>
        <td>
            <h6>Nome: {{$namehtml}}</h6>
        </td>
    </tr>
    <tr>
        <td>
            <h6>Número: {{$numerohtml}}</h6>
        </td>
    </tr>
    <tr>
        <td>
            <h6>Curso: {{$cursohtml}}</h6>
        </td>
    </tr>
</table>
@endsection

