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
            // kolom integer wajib isi, default 0 agar tidak error saat migrasi
            $table->integer('common_ingredients_count')->notnull()->default(0)->after('product_id');//->default(0);

            // kolom longtext wajib isi, default string kosong agar tetap valid
            $table->longText('common_ingredients')->nullable()->after('common_ingredients_count');//->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recommendation_results', function (Blueprint $table) {
            $table->dropColumn(['common_ingredients_count', 'common_ingredients']);
        });
    }
};
