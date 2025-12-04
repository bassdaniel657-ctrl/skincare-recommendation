<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationResultsTable extends Migration
{
  public function up()
  {
    Schema::create('evaluation_results', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_query_id')->constrained('user_queries')->onDelete('cascade');  // Relasi dengan user_queries
      $table->foreignId('product_id')->constrained()->onDelete('cascade');  // Relasi dengan produk
      $table->integer('common_ingredients_count');  // Jumlah bahan yang umum dengan query
      $table->json('common_ingredients');  // Daftar bahan yang umum
      $table->decimal('similarity_score', 8, 6);  // Skor kesamaan
      $table->boolean('is_relevant');  // Apakah produk relevan dengan query
      $table->decimal('precision', 5, 3);  // Precision
      $table->decimal('recall', 5, 3);  // Recall
      $table->decimal('f1_score', 5, 3);  // F1-Score
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('evaluation_results');
  }
}
