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
            if (Schema::hasColumn('evaluation_results', 'recall')) {
                $table->dropColumn('recall');
            }
            if (Schema::hasColumn('evaluation_results', 'f1_score')) {
                $table->dropColumn('f1_score');
            }
            $table->decimal('hitrate', 15, 10)->nullable();
            $table->decimal('mrr', 15, 10)->nullable();
            $table->decimal('map', 15, 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_results', function (Blueprint $table) {
            $table->float('recall')->nullable();
            $table->float('f1_score')->nullable();
        });
    }
};
