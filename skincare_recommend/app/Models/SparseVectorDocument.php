<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparseVectorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sparse_vector',
    ];

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
