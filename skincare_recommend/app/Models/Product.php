<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'brand_name',
        'details',
        'ingredients',
        'price',
        'category',
    ];

    // Relasi ke RecommendationResult
    public function recommendations()
    {
        return $this->hasMany(RecommendationResult::class);
    }
}
