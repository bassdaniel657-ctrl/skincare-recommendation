<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetricsToUserQueriesTable extends Migration
{
  public function up()
  {
    Schema::table('user_queries', function (Blueprint $table) {
      $table->decimal('precision', 5, 3)->nullable();  // Kolom untuk precision
      $table->decimal('recall', 5, 3)->nullable();     // Kolom untuk recall
      $table->decimal('f1_score', 5, 3)->nullable();   // Kolom untuk f1_score
      $table->integer('true_positives')->nullable();   // Kolom untuk true positives
      $table->integer('false_positives')->nullable();  // Kolom untuk false positives
      $table->integer('false_negatives')->nullable(); // Kolom untuk false negatives
      $table->integer('total_relevant')->nullable();  // Kolom untuk total relevan
      $table->decimal('precision_euclidean', 5, 3)->nullable();  // Kolom untuk precision
      $table->decimal('recall_euclidean', 5, 3)->nullable();     // Kolom untuk recall
      $table->decimal('f1_score_euclidean', 5, 3)->nullable();   // Kolom untuk f1_score
      $table->integer('true_positives_euclidean')->nullable();   // Kolom untuk true positives
      $table->integer('false_positives_euclidean')->nullable();  // Kolom untuk false positives
      $table->integer('false_negatives_euclidean')->nullable(); // Kolom untuk false negatives
      $table->integer('total_relevant_euclidean')->nullable();  // Kolom untuk total relevan
    });
  }

  public function down()
  {
    Schema::table('user_queries', function (Blueprint $table) {
      $table->dropColumn([
        'precision',
        'recall',
        'f1_score',
        'true_positives',
        'false_positives',
        'false_negatives',
        'total_relevant',
        'precision_euclidean',
        'recall_euclidean',
        'f1_score_euclidean',
        'true_positives_euclidean',
        'false_positives_euclidean',
        'false_negatives_euclidean',
        'total_relevant_euclidean'
      ]);
    });
  }
}
