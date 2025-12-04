<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuery extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'query',
  ];

  // Relasi ke User
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Relasi ke RecommendationResult
  public function recommendations()
  {
    return $this->hasMany(RecommendationResult::class);
  }

  // Relasi dengan EvaluationResult
  public function evaluations()
  {
    return $this->hasMany(EvaluationResult::class);
  }
}
