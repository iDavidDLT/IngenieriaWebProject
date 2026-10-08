<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'sku', 'description', 'price', 'stock'])]
class Product extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'stock' => 'integer', 'user_id' => 'integer'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
