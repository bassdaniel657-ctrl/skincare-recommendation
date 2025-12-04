<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EvaluationService;
use App\Models\RecommendationResult;
use Illuminate\Support\Facades\DB;

class EvaluateController extends Controller
{
    public function index()
    {
        return view('admin.evaluate.index');
    }

    public function hitrate(){
        $queryCount = RecommendationResult::select('user_query_id')->distinct()->count();
        $euclidean = RecommendationResult::where('similarity_type', 'euclidean')->get();
        $euclidean_total = $euclidean->count();

        $euclidean_hitrate = RecommendationResult::where('similarity_type', 'euclidean')
            ->where('is_relevant', 1)->get()->unique('user_query_id')->count();
        $euclidean_hitrate = $euclidean_hitrate / $queryCount;
        $euclidean_feedback = RecommendationResult::where('similarity_type', 'euclidean')
            ->whereNotNull('is_relevant')->count();

        $cosine = RecommendationResult::where('similarity_type', 'cosine')->get();
        $cosine_hitrate = RecommendationResult::where('similarity_type', 'cosine')
            ->where('is_relevant', 1)->get()->unique('user_query_id')->count();
        $cosine_hitrate = $cosine_hitrate / $queryCount;
        $cosine_total = $cosine->count();
        $cosine_feedback = RecommendationResult::where('similarity_type', 'cosine')
            ->whereNotNull('is_relevant')->count();
        
        $results = DB::table('evaluation_results')
        ->join('user_queries', 'evaluation_results.user_query_id', '=', 'user_queries.id')
        ->select(
            'evaluation_results.precision',
            'evaluation_results.mrr',
            'evaluation_results.map',
            'evaluation_results.similarity_type',
            'user_queries.query',
        )
        ->get();

        $cosine_p = $results->where('similarity_type', 'cosine')->avg('precision');
        $cosine_mrr = $results->where('similarity_type', 'cosine')->avg('mrr');
        $cosine_map = $results->where('similarity_type', 'cosine')->avg('map');

        $euclidean_p = $results->where('similarity_type', 'euclidean')->avg('precision');
        $euclidean_mrr = $results->where('similarity_type', 'euclidean')->avg('mrr');
        $euclidean_map = $results->where('similarity_type', 'euclidean')->avg('map');
                
        $data = [
            'euclidean' => [
                'data' => $results->where('similarity_type', 'euclidean')->toArray(),
                'total' => $euclidean_total,
                'hitrate' => $euclidean_hitrate,
                'feedback' => $euclidean_feedback,
                'precision' => $euclidean_p,
                'mrr' => $euclidean_mrr,
                'map' => $euclidean_map,
            ],
            'cosine' => [
                'data' => $results->where('similarity_type', 'cosine')->toArray(),
                'total' => $cosine_total,
                'hitrate' => $cosine_hitrate,
                'feedback' => $cosine_feedback,
                'precision' => $cosine_p,
                'mrr' => $cosine_mrr,
                'map' => $cosine_map,
            ],
        ];

        return view('admin.evaluate.hitrate', compact('data'));
    }

    public function update(){
        EvaluationService::update();

        return response()->json([
            'message' => 'Sparse vector TF-IDF berhasil diperbarui.',
            'status' => 'success'
        ]);
    }
}
