<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'product_id', 'action', 'sku', 'product_name'])]
class ProductAudit extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
