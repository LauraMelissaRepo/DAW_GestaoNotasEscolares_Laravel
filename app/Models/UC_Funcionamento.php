<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UC_Funcionamento extends Model
{
    use HasFactory;

    public function anoLetivo() {
        return $this->hasMany('App\Models\AnoLetivo');
    }

    public function uc() {
        return $this->hasMany('App\Models\UC');
    }


    public function incricaoMatricula() {
        return $this->hasMany('App\Models\Inscricao_Matricula');
    }
}
