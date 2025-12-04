<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationResult extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_query_id',
    'similarity_type',
    'precision',
    'mrr',
    'hitrate',
    'map'
  ];

  // Relasi dengan UserQuery
  public function userQuery()
  {
    return $this->belongsTo(UserQuery::class);
  }
}
