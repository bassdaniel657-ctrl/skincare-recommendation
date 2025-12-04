<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corpus extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocab',
        'idf_values',
    ];
}
