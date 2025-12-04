<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserQuery;
use App\Models\RecommendationResult;
use App\Services\EvaluationService;

class HistoryController extends Controller
{
    /**
     * Tampilkan daftar riwayat query milik user yang sedang login.
     */
    public function index()
    {
        $history = UserQuery::with(['recommendations', 'evaluations'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.history.index', compact('history'));
    }

    /**
     * Tampilkan detail hasil rekomendasi & evaluasi dari 1 query user.
     */
    public function show($id)
    {
        $history = UserQuery::with(['recommendations', 'evaluations'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $recommendations = RecommendationResult::where('user_query_id', $history->id)->get();
        $metrics = EvaluationService::getResults($history->id);

        return view('user.history.show', compact('history', 'recommendations', 'metrics'));
    }
}
