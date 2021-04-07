<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUCFuncionamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_c__funcionamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anoletivo_id');
            $table->index('anoletivo_id');
            $table->unsignedBigInteger('uc_id');
            $table->index('uc_id');
            $table->unsignedBigInteger('incricaoMatricula_id');
            $table->index('incricaoMatricula_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_c__funcionamentos');
    }
}
