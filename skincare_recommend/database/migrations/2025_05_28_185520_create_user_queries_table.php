<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserQueriesTable extends Migration
{
  public function up()
  {
    Schema::create('user_queries', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Relasi dengan tabel users
      $table->text('query');  // Kata kunci yang dicari oleh user
      $table->json('recommended_products');  // Produk yang direkomendasikan (ID produk dan skor kesamaan)
      $table->json('recommended_products_euclidean');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('user_queries');
  }
}
