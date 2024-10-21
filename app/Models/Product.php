<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','image','price','slug','is_active'];

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
}
