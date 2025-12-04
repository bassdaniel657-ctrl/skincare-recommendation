<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('user_queries', function (Blueprint $table) {
      $table->decimal('mrr', 5, 3)->default(0)->after('f1_score'); // Mean Reciprocal Rank
      $table->tinyInteger('hit_rate')->default(0)->after('mrr'); // Hit Rate (0 or 1)
      $table->decimal('mrr_euclidean', 5, 3)->default(0)->after('hit_rate'); // Mean Reciprocal Rank
      $table->tinyInteger('hit_rate_euclidean')->default(0)->after('mrr_euclidean'); // Hit Rate (0 or 1)
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('user_queries', function (Blueprint $table) {
      $table->dropColumn(['mrr', 'hit_rate', 'mrr_euclidean', 'hit_rate_euclidean']);
    });
  }
};
