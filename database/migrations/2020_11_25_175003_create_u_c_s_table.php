<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUCSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_c_s', function (Blueprint $table) {
            $table->id();
            $table->string('nome_uc');
            $table->unsignedBigInteger('curso_id');
            $table->index('curso_id');
            $table->unsignedBigInteger('semestre_id');
            $table->index('semestre_id');
            $table->unsignedBigInteger('docente_id');
            $table->index('docente_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_c_s');
    }
}
