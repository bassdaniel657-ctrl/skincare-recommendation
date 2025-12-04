<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecommendationService;
use App\Services\EvaluationService;
use App\Models\RecommendationResult;

class ProductController extends Controller
{
    /**
     * Tampilkan form input query pengguna.
     */
    public function showForm()
    {
        return view('recommendation.form');
    }

    /**
     * Jalankan proses rekomendasi berdasarkan query pengguna.
     * Pipeline:
     * - Preprocessing → TF-IDF → Similarity (Cosine & Euclidean) → Ranking
     * - Simpan hasil ke database (tabel user_queries & recommendation_results)
     */
    public function generate(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3'
        ]);

        // Jalankan service utama untuk rekomendasi
        $result = RecommendationService::generate(auth()->id(), $request->input('query'));

        return view('user.result', [
            'query' => $request->input('query'),
            'cosineResults' => $result['cosine'],
            'euclideanResults' => $result['euclidean'],
            'userQuery' => $result['userQuery'],
        ]);
    }

    /**
     * Simpan feedback user untuk hasil rekomendasi tertentu.
     * Feedback akan memicu proses evaluasi ulang (Precision, MRR, Hit Rate).
     */

    public function updateFeedback(Request $request)
    {
        $request->validate([
            'user_query_id' => 'required|exists:user_queries,id',
            'product_id' => 'required|exists:products,id',
            'feedback' => 'required|boolean',
            'similarity_type' => 'required|in:cosine,euclidean'
        ]);

        dump($request->all());

        // Temukan hasil rekomendasi berdasarkan ID
        $recommendation = RecommendationResult::where('user_query_id', $request->user_query_id)
            ->where('product_id', $request->product_id)
            ->where('similarity_type', $request->similarity_type)
            ->where('product_id', $request->product_id)
            ->firstOrFail();

        // Simpan feedback user
        $recommendation->update([
            'is_relevant' => $request->feedback ? 1 : 0,
            'feedback_at' => now(),
        ]);

        // Jalankan evaluasi setelah feedback
        EvaluationService::evaluateAfterFeedback($recommendation->query_id);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil diperbarui dan evaluasi diperbarui.'
        ]);
    }
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'user_query_id' => 'required|exists:user_queries,id',
            'product_id' => 'required|exists:products,id',
            'feedback' => 'required|boolean',
            'similarity_type' => 'required|in:cosine,euclidean'
        ]);

        // Temukan hasil rekomendasi berdasarkan query, produk, dan metode
        $recommendation = RecommendationResult::where('user_query_id', $request->user_query_id)
            ->where('product_id', $request->product_id)
            ->where('similarity_type', $request->similarity_type)
            ->firstOrFail();

        // Simpan feedback user
        $recommendation->update([
            'is_relevant' => $request->feedback ? 1 : 0,
            'feedback_at' => now(),
        ]);

        // Jalankan evaluasi setelah feedback
        EvaluationService::evaluateAfterFeedback($request->user_query_id);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil disimpan dan evaluasi diperbarui.'
        ]);
    }

    /**
     * Ambil hasil evaluasi terbaru berdasarkan feedback user.
     */
    public function getEvaluation($userQueryId)
    {
        $metrics = EvaluationService::getResults($userQueryId);

        return response()->json([
            'success' => true,
            'data' => $metrics,
        ]);
    }
}
