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
        Schema::table('recommendation_results', function (Blueprint $table) {
            // Pastikan kolom lama ada sebelum rename
            if (Schema::hasColumn('recommendation_results', 'query_id')) {
                $table->renameColumn('query_id', 'user_query_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recommendation_results', function (Blueprint $table) {
            if (Schema::hasColumn('recommendation_results', 'user_query_id')) {
                $table->renameColumn('user_query_id', 'query_id');
            }
        });
    }
};
