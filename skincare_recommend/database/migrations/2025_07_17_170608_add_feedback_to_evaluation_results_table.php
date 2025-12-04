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
            $table->boolean('user_feedback')->nullable()->after('f1_score'); // true = like, false = dislike, null = no feedback
            $table->timestamp('feedback_timestamp')->nullable()->after('user_feedback');
            $table->string('recommendation_type')->default('cosine')->after('feedback_timestamp'); // 'cosine' or 'euclidean'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_results', function (Blueprint $table) {
            $table->dropColumn(['user_feedback', 'feedback_timestamp', 'recommendation_type']);
        });
    }
};
