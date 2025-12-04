<?php

namespace App\Services;

use App\Models\EvaluationResult;
use App\Models\RecommendationResult;
use Illuminate\Support\Facades\DB;

class EvaluationService
{
    /**
     * Hitung evaluasi (Precision, MRR, Hit Rate) setelah user memberikan feedback.
     */
    public static function evaluateAfterFeedback($userQueryId)
    {
        foreach (['cosine', 'euclidean'] as $method) {
            // Ambil daftar produk rekomendasi dan relevan
            $recommendedIds = RecommendationResult::where('user_query_id', $userQueryId)
                ->where('similarity_type', $method)
                ->orderBy('rank', 'asc')
                ->pluck('product_id')
                ->toArray();

            $relevantIds = RecommendationResult::where('user_query_id', $userQueryId)
                ->where('similarity_type', $method)
                ->where('is_relevant', 1) // berdasarkan feedback user
                ->pluck('product_id')
                ->toArray();

            // Hitung Precision
            $tp = count(array_intersect($recommendedIds, $relevantIds));
            $precision = count($recommendedIds) > 0 ? $tp / count($recommendedIds) : 0;

            // Hitung MRR
            $mrr = 0;
            foreach ($recommendedIds as $rank => $id) {
                if (in_array($id, $relevantIds)) {
                    $mrr = 1 / ($rank + 1);
                    break;
                }
            }

            // Hitung Hit Rate
            $hitRate = $tp > 0 ? 1 : 0;

            // Simpan hasil ke evaluation_results
            EvaluationResult::updateOrCreate(
                ['user_query_id' => $userQueryId, 'similarity_type' => $method], 

                [
                    'precision' => round($precision, 3),
                    'mrr' => round($mrr, 3),
                    'hit_rate' => $hitRate,
                ]
            );
        }
    }
    public static function getResults($userQueryId)
    {
    return \App\Models\EvaluationResult::where('user_query_id', $userQueryId)
        ->orderBy('created_at', 'desc')
        ->first();
    }

    public static function precision()
    {
        $cosine_p = [];
        $cosine_tp = DB::table('recommendation_results')
            ->where('similarity_type', 'cosine')
            ->where('is_relevant', 1)
            ->select('user_query_id')
            ->get()->groupBy('user_query_id')->toArray();

        $cosine_fp = DB::table('recommendation_results')
            ->where('similarity_type', 'cosine')
            ->where('is_relevant', 0)
            ->select('user_query_id')
            ->get()->unique('user_query_id')->count();

        foreach($cosine_tp as $key => $value){
            $cosine_p[$key] = count($value) / ($cosine_fp + count($value));
        }
        dump($cosine_p);

        $euclidean_p = [];
        $euclidean_tp = DB::table('recommendation_results')
            ->where('similarity_type', 'euclidean')
            ->where('is_relevant', 1)
            ->select('user_query_id')
            ->get()->groupBy('user_query_id')->toArray();
        
        $euclidean_fp = DB::table('recommendation_results')
            ->where('similarity_type', 'euclidean')
            ->where('is_relevant', 0)
            ->select('user_query_id')
            ->get()->unique('user_query_id')->count();

        foreach($euclidean_tp as $key => $value){
            $euclidean_p[$key] = count($value) / ($euclidean_fp + count($value));
        }

        foreach ($euclidean_p as $index => $value) {
            EvaluationResult::updateOrCreate(
                [
                    'user_query_id' => $index,
                    'similarity_type' => 'euclidean',
                ],
                [
                    'precision' => $value,
                    'similarity_type' => 'euclidean',
                ],
            );
        }
        foreach ($cosine_p as $index => $value) {
            EvaluationResult::updateOrCreate(
                [
                    'user_query_id' => $index,
                    'similarity_type' => 'cosine'
                ],
                [
                    'precision' => $value,
                    'similarity_type' => 'cosine',
                ],
            );
        }       
    }

    public static function rr()
    {
        $euclidean_rank = DB::table('recommendation_results')
            ->where('similarity_type', 'euclidean')
            ->where('is_relevant', 1)
            ->select('user_query_id', DB::raw('MIN(`rank`) as min_rank'))
            ->groupBy('user_query_id')
            ->get()->pluck('min_rank', 'user_query_id')->toArray();

        foreach($euclidean_rank as $index => $rank){
            EvaluationResult::updateOrCreate(
                [
                    'user_query_id' => $index,
                    'similarity_type' => 'euclidean',
                ],
                [
                    'mrr' => 1 / $rank,
                    'similarity_type' => 'euclidean',
                ],
            );
        }

        $cosine_rank = DB::table('recommendation_results')
            ->where('similarity_type', 'cosine')
            ->where('is_relevant', 1)
            ->select('user_query_id', DB::raw('MIN(`rank`) as min_rank'))
            ->groupBy('user_query_id')
            ->get()->pluck('min_rank', 'user_query_id')->toArray();

        foreach($cosine_rank as $index => $rank){
            EvaluationResult::updateOrCreate(
                [
                    'user_query_id' => $index,
                    'similarity_type' => 'cosine',
                ],
                [
                    'mrr' => 1 / $rank,
                    'similarity_type' => 'cosine',
                ],
            );
        }
    }

    public static function ap(){
        $euclidean_ap = DB::table('recommendation_results')
            ->where('similarity_type', 'euclidean')
            ->where('is_relevant', 1)
            ->select('user_query_id', 'rank')
            ->get();
        $euclidean_ap = $euclidean_ap->groupBy('user_query_id')->toArray();

        foreach($euclidean_ap as $user_query_id => $item){
            $p = [];
            foreach($item as $i => $data){
                $p[] = ($i + 1) / $data->rank;
            }
            $ap = array_sum($p) / count($p);

            EvaluationResult::updateOrCreate(
                [
                    'user_query_id' => $user_query_id,
                    'similarity_type' => 'euclidean',
                ],
                [
                    'map' => $ap,
                    'similarity_type' => 'euclidean',
                ],
            );
        }

        $cosine_ap = DB::table('recommendation_results')
            ->where('similarity_type', 'cosine')
            ->where('is_relevant', 1)
            ->select('user_query_id', 'rank')
            ->get();
        $cosine_ap = $cosine_ap->groupBy('user_query_id')->toArray();

        foreach($cosine_ap as $user_query_id => $item){
            $p = [];
            foreach($item as $i => $data){
                $p[] = ($i + 1) / $data->rank;
            }
            $ap = array_sum($p) / count($p);

            EvaluationResult::updateOrCreate(
                [
                    'user_query_id' => $user_query_id,
                    'similarity_type' => 'cosine',
                ],
                [
                    'map' => $ap,
                    'similarity_type' => 'cosine',
                ],
            );
        }

    }

    public static function update(){
        self::precision();
        self::rr();
        self::ap();
    }
}
