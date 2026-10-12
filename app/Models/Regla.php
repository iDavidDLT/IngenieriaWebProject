<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regla extends Model
{
    protected $fillable = ['porcentaje', 'umbral_monto'];
}
