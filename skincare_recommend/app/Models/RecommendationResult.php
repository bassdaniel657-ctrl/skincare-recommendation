<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_query_id',
        'product_id',
        'product_name',
        'similarity_type', // cosine / euclidean
        'similarity_score',
        'common_ingredients_count',
        'common_ingredients',
        'is_relevant',
        'feedback_at',
        'rank',
    ];

    protected $casts = [
        'common_ingredients' => 'array',
        'feedback_at' => 'datetime',
    ];

    // Relasi ke UserQuery
    public function userQuery()
    {
        return $this->belongsTo(UserQuery::class, 'user_query_id');
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
