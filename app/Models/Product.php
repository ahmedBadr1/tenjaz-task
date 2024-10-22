<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','image','slug','is_active'];

    public static function boot(): void
    {
        parent::boot();
        // generate slug if it's not already there
        self::creating(function ($model) {
           if (!$model->slug) {
               $model->slug = Str::slug($model->name);
           }
        });
    }

    // A product has many price tiers
    public function prices()
    {
        return $this->hasMany(PriceTier::class);
    }

    // Function to get price based on user type
    public function getPriceForUser($type)
    {
        return $this->prices()->where('type', $type)->value('price') ?? null;
    }

    public function isActiveScope($query,$isActive = 1)
    {
        return $query->where('is_active', $isActive);
    }
}
