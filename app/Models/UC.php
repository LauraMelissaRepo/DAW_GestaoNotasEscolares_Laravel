<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UC extends Model
{
    use HasFactory;

    public function curso() {
        return $this->hasMany('App\Models\Curso');
    }

    public function semestre() {
        return $this->hasMany('App\Models\Semestre');
    }

    public function docente() {
        return $this->hasMany('App\Models\Docente');
    }
}
