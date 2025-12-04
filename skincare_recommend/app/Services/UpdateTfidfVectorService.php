<?php

namespace App\Services;

use App\Models\Corpus;
use App\Models\Product;
use App\Services\PreprocessingService;
use App\Services\TfidfService;
use App\Models\SparseVectorDocument;

class UpdateTfidfVectorService
{
    public static function update()
    {
        $products = Product::all();
        $documents = [];
        $productIds = [];

        foreach ($products as $product) {
            $processed = PreprocessingService::preprocess($product->ingredients);
            $documents[] = PreprocessingService::tokenize($processed);
            $productIds[] = $product->id;
        }

        $vocab = TfidfService::buildVocabulary($documents);
        $tf = TfidfService::computeTf($documents, $vocab);
        $idf = TfidfService::computeIdf($documents, $vocab);
        $tfidfDocs = TfidfService::computeTfidf($tf, $idf, $vocab);

        foreach ($tfidfDocs as $index => $tfidfVector) {
            SparseVectorDocument::updateOrCreate(
                ['product_id' => $productIds[$index]],
                ['sparse_vector' => json_encode($tfidfVector)],
            );
        }

        foreach ($idf as $term => $value) {
            Corpus::updateOrCreate(
                ['vocab' => $term],
                ['idf_values' => $value]
            );
        }
    }

}