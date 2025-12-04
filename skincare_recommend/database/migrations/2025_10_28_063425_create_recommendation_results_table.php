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
        Schema::create('recommendation_results', function (Blueprint $table) {
            $table->id();

            // Hubungan ke user_queries
            $table->foreignId('query_id')->constrained('user_queries')->onDelete('cascade');

            // Hubungan ke produk (asumsikan table products ada)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // Nama produk (opsional, untuk kemudahan tampilan/debug)
            $table->string('product_name')->nullable();

            // Tipe similarity / metode: cosine atau euclidean
            $table->enum('similarity_type', ['cosine', 'euclidean']);

            // Skor similarity (decimal dengan presisi)
            $table->decimal('similarity_score', 10, 6);

            // Peringkat hasil (optional)
            $table->integer('rank')->nullable();

            // Feedback dari user: null = belum ada, true = suka, false = tidak suka
            $table->boolean('is_relevant')->nullable()->default(null);

            // Waktu ketika user memberi feedback
            $table->timestamp('feedback_at')->nullable();

            $table->timestamps();

            // Pastikan tidak duplikat: satu query + satu produk + satu metode hanya 1 baris
            $table->unique(['query_id', 'product_id', 'similarity_type'], 'uq_query_product_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_results');
    }
};
