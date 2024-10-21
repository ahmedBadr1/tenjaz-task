<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceTier extends Model
{
    protected $fillable = ['product_id', 'type', 'price'];

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
