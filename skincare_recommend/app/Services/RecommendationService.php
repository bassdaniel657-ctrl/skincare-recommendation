<?php

namespace App\Services;

use App\Models\Corpus;
use App\Models\Product;
use App\Models\UserQuery;
use App\Models\RecommendationResult;
use App\Models\SparseVectorDocument;
use App\Services\PreprocessingService;
use App\Services\TfidfService;
use App\Services\SimilarityService;

class RecommendationService
{
    public static function generate($userId, $query)
    {
        $queryProcessed = PreprocessingService::preprocess($query);
        $queryTerms = PreprocessingService::tokenize($queryProcessed);

        // dump($queryProcessed);
        // dump($queryTerms);

        // $products = Product::all();
        // $documents = [];
        $productIds = [];

        $tfidfDocs = [];
        $vocab = [];
        
        $getVector = SparseVectorDocument::select('product_id', 'sparse_vector')->get();
        $idf = Corpus::pluck('idf_values', 'vocab')->toArray();
        $vocab = TfidfService::buildVocabulary(Corpus::pluck('vocab'));

        foreach($getVector as $item){
            $productIds[] = $item->product_id;
            $tfidfDocs[] = json_decode($item->sparse_vector, true);
        }
        
        // foreach ($products as $product) {
        //     $processed = PreprocessingService::preprocess($product->ingredients);
        //     $documents[] = PreprocessingService::tokenize($processed);
        //     $productIds[] = $product->id;
        // }

        // $vocab = TfidfService::buildVocabulary($documents);
        // $tf = TfidfService::computeTf($documents, $vocab);
        // $idf = TfidfService::computeIdf($documents, $vocab);
        // $tfidfDocs = TfidfService::computeTfidf($tf, $idf, $vocab);
        
        // Buat TF-IDF query
        $queryTf = array_fill_keys($vocab, 0);
        foreach ($queryTerms as $term) {
            foreach ($vocab as $item) {
                if (strpos($item, $term) !== false) {
                    $queryTf[$item] = 1;
                }
            }
        }

        $queryTfidf = [];
        foreach ($vocab as $term) {
            $queryTfidf[$term] = $queryTf[$term] * $idf[$term];
        }

        // Hitung kesamaan
        $cosine = [];
        $euclidean = [];
        foreach ($tfidfDocs as $i => $docVec) {
            $cosine[$productIds[$i]] = SimilarityService::cosine($queryTfidf, $docVec);
            $euclidean[$productIds[$i]] = SimilarityService::euclidean($queryTfidf, $docVec);
        }

        // Ambil top 5
        arsort($cosine);
        asort($euclidean);
        $topCosine = array_slice($cosine, 0, 10, true);
        $topEuclidean = array_slice($euclidean, 0, 10, true);

        // Simpan user query
        $userQuery = UserQuery::create([
            'user_id' => $userId,
            'query' => implode('|', $queryTerms),
        ]);

        // Simpan hasil rekomendasi
        foreach (['cosine' => $topCosine, 'euclidean' => $topEuclidean] as $type => $results) {
            $rank = 1;
            foreach ($results as $pid => $score) {
                $product = Product::find($pid);
                $processed = PreprocessingService::preprocess($product->ingredients);
                $ingredients = PreprocessingService::tokenize($processed);
                $common = array_intersect($queryTerms, $ingredients);

                RecommendationResult::create([
                    'user_query_id' => $userQuery->id,
                    'product_id' => $pid,
                    'product_name' => $product->product_name,
                    'similarity_type' => $type,
                    'similarity_score' => $score,
                    'common_ingredients_count' => count($common),
                    'common_ingredients' => implode(', ', $common),
                    'rank' => $rank++,
                    'is_relevant' => null,
                ]);
            }
        }

        return [
            'userQuery' => $userQuery,
            'cosine' => RecommendationResult::where('user_query_id', $userQuery->id)
                    ->where('similarity_type', 'cosine')
                    ->orderBy('rank')
                    ->get(),
            'euclidean' => RecommendationResult::where('user_query_id', $userQuery->id)
                    ->where('similarity_type', 'euclidean')
                    ->orderBy('rank')
                    ->get(),
        ];
    }
}
