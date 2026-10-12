<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = ['vendedor_id', 'fecha_venta', 'monto'];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}
