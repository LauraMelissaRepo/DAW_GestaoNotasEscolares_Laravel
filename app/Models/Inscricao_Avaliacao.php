<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscricao_Avaliacao extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function avaliacao() {
        return $this->hasMany('App\Models\Avaliacao');
    }

    public function aluno() {
        return $this->hasMany('App\Models\Aluno');
    }

    public function classificacao() {
        return $this->hasOne('App\Models\Classificacao');
    }

}
