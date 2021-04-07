<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;
    public $timestamps = false;


    public function docente() {
        return $this->hasMany('App\Models\Docente');
    }
}
