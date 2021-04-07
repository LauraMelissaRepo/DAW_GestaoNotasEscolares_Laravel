<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscricao_Matricula extends Model
{
    use HasFactory;

    public function aluno() {
        return $this->hasMany('App\Models\Aluno');
    }
}
