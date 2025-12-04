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
        Schema::table('evaluation_results', function (Blueprint $table) {
            // Lepas foreign key dulu supaya bisa ubah struktur
            if (Schema::hasColumn('evaluation_results', 'product_id')) {
                try {
                    $table->dropForeign(['product_id']);
                } catch (\Exception $e) {
                    // Jika constraint sudah dihapus sebelumnya, biarkan saja
                }
            }

            $columnsToDrop = [
                'product_id', 'common_ingredients_count', 'common_ingredients',
                'similarity_score', 'is_relevant', 'user_feedback', 'feedback_timestamp',
                'recommendation_type'
            ];

            foreach ($columnsToDrop as $col){
                if (Schema::hasColumn('evaluation_results', $col)){
                    $table->dropColumn($col);
                }
            }

            if(!Schema::hasColumn('evaluation_results', 'similarity_type')){
                $table->enum('similarity_type', ['cosine', 'euclidean'])->after('user_query_id');
            }

            //$table->foreign('user_query_id')->references('id')->on('user_queries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_results', function (Blueprint $table) {
            if (Schema::hasColumn('evaluation_results', 'similarity_type')) {
                $table->dropColumn('similarity_type');
            }

        });
    }
};
