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
            $columnsToDrop = [
                'recommended_products', 'precision', 'mrr', 'hit_rate',
                'true_positives', 'false_positives', 'false_negatives', 'total_relevant',
                'recommended_products_euclidean', 'precision_euclidean',
                'mrr_euclidean', 'hit_rate_euclidean', 'true_positives_euclidean',
                'false_positives_euclidean', 'false_negatives_euclidean',
                'total_relevant_euclidean', 'recall', 'f1_score', 
                'recall_euclidean', 'f1_score_euclidean'
            ];

            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('user_queries', $col)) {
                    $table->dropColumn($col);
                }
            }
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
