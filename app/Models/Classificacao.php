<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classificacao extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function inscricaoAvaliacao(){
        return $this->hasOne('App\Models\Inscricao_Avaliacao');
    }
}
