<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';
    protected $fillable = ['nombre', 'email'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
