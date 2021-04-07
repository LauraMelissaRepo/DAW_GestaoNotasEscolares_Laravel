<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricaoAvaliacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricao__avaliacaos', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->unsignedBigInteger('avaliacao_id');
            $table->index('avaliacao_id');
            $table->unsignedBigInteger('aluno_id');
            $table->index('aluno_id');
            $table->unsignedBigInteger('classificacao_id');
            $table->index('classificacao_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscricao__avaliacaos');
    }
}
