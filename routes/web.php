<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/aluno/perfilAluno', 'App\Http\Controllers\AlunoController@perfilAluno')->name('home_aluno');

Route::get('/docente/perfilDocente', 'App\Http\Controllers\DocenteController@perfilDocente')->name('home_docente');

//Route::get('/home/{tipo}', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Vistas para o aluno
Route::get('/aluno/consultarnotas', 'App\Http\Controllers\AlunoController@consultarNotas')->name('consultarNotasAluno');
Route::post('/aluno/consultarnotasCadeira', 'App\Http\Controllers\AlunoController@mostrarCadeiras')->name('get_Cadeira_Aluno_ConsultarNotas')->middleware('verificar_anoletivosemestre_consultarNotas');
Route::post('/aluno/consultarnotasverfiltro', 'App\Http\Controllers\AlunoController@consultarNotasAlunofilter')->name('get_listConsultarNotasAlunofilter')->middleware('verificar_cadeira_consultarNotas');
Route::post('/aluno/consultarnotasvertodas', 'App\Http\Controllers\AlunoController@consultarNotasAlunoTodas')->name('get_listConsultarNotasAluno');
Route::get('/aluno/inscreverexames', 'App\Http\Controllers\AlunoController@inscreverExames');
Route::get('/aluno/inscreverexames/recursos', 'App\Http\Controllers\AlunoController@mostrarRecursos')->name('get_recursos');
Route::get('/aluno/inscreverexames/melhorias', 'App\Http\Controllers\AlunoController@mostrarMelhorias')->name('get_melhorias');
Route::post('/aluno/inscreverexames/insertR_M', 'App\Http\Controllers\AlunoController@insertRecursoMelhoria')->name('insert_recurso_melhoria');
Route::get('/aluno/inscreverexames/todos', 'App\Http\Controllers\AlunoController@mostrarTodos')->name('get_todos');
Route::get('/aluno/calendario', 'App\Http\Controllers\AlunoController@verCalendario');
Route::post('/aluno/calendario1', 'App\Http\Controllers\AlunoController@showCalendario')->name('get_date_consulta_calendario');

//Vistas para o docente
Route::get('/docente/marcaraval', 'App\Http\Controllers\DocenteController@marcarAvaliacao')->name('marAval');
Route::post('/docente/marcaraval', 'App\Http\Controllers\DocenteController@showCalUC')->name('get_date_uc')->middleware('verificar_cadeira_marcaravaliacao');
Route::post('/docente/calendario/insert', 'App\Http\Controllers\DocenteController@dbAvalInsert')->name('insertAvalTable')->middleware('verificar_nomeavaliacao_marcaravaliacao');
Route::get('/docente/lancarnotas', 'App\Http\Controllers\DocenteController@lancarNotas')->name('lancarNotas');
Route::post('/docente/lancarnotasFinalStep', 'App\Http\Controllers\DocenteController@showChairLancar')->name('get_items_Choose_Chair_Lancar')->middleware('verificar_cadeiraepoca_lancarNotas');
Route::post('/docente/lancarnotasList', 'App\Http\Controllers\DocenteController@mostrarListaNotasMarcar')->name('get_listLancarNotas')->middleware('verificar_avaliacao_lancarNotas');
Route::post('/docente/lancarnotasInsert', 'App\Http\Controllers\DocenteController@insertNotasTable')->name('insert_Notas_table');
Route::get('/docente/consultarnotas', 'App\Http\Controllers\DocenteController@consultarNotas')->name('consultarNotas');
Route::post('/docente/consultarnotasverFinalStep', 'App\Http\Controllers\DocenteController@showChairConsultar')->name('get_items_Choose_Chair_Consultar')->middleware('verificar_cadeiraepoca_consultarNotas');
Route::post('/docente/consultarnotasverList', 'App\Http\Controllers\DocenteController@mostrarListaNotasConsultar')->name('get_listConsultarNotas')->middleware('verificar_avaliacao_consultarNotas');

