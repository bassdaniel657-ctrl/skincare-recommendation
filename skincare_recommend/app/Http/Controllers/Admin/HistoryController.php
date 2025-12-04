<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserQuery;
use App\Models\RecommendationResult;
use App\Http\Controllers\Controller;
use App\Services\EvaluationService;

class HistoryController extends Controller
{
    /**
     * Tampilkan seluruh riwayat query pengguna (semua user).
     */
    public function index()
    {
        // Ambil semua query beserta user dan hasil evaluasi
        $history = UserQuery::with(['user', 'recommendations', 'evaluations'])->latest()->get();

        return view('admin.history.index', compact('history'));
    }

    /**
     * Tampilkan detail riwayat query tertentu (berdasarkan ID).
     */
    public function show($id)
    {
        $history = UserQuery::with(['user', 'recommendations', 'evaluations'])->findOrFail($id);

        // Ambil semua hasil rekomendasi yang terkait dengan query ini
        $recommendations = RecommendationResult::where('user_query_id', $history->id)->get();

        // Hitung evaluasi setelah feedback (melalui EvaluationService)
        $metrics = EvaluationService::getResults($history->id);

        return view('admin.history.show', compact('history', 'recommendations', 'metrics'));
    }
}
