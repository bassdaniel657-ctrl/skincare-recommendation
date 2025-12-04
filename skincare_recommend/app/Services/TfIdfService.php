<?php

namespace App\Services;

class TfidfService
{
    public static function buildVocabulary($documents)
    {
        return collect($documents)
            ->flatten()
            ->unique()
            ->values()
            ->all();
    }

    public static function computeTf($documents, $vocab)
    {
        $tf = [];
        foreach ($documents as $doc) {
            $tfDoc = [];
            foreach ($vocab as $term) {
                $tfDoc[$term] = array_count_values($doc)[$term] ?? 0;
            }
            $tf[] = $tfDoc;
        }
        return $tf;
    }

    public static function computeIdf($documents, $vocab)
    {
        $df = array_fill_keys($vocab, 0);
        foreach ($vocab as $term) {
            foreach ($documents as $doc) {
                if (in_array($term, $doc)) {
                    $df[$term]++;
                }
            }
        }

        $N = count($documents);
        $idf = [];
        foreach ($df as $term => $freq) {
            $idf[$term] = log(($N + 1) / ($freq + 1)) + 1;
        }
        return $idf;
    }

    public static function computeTfidf($tf, $idf, $vocab)
    {
        $tfidfDocs = [];
        foreach ($tf as $docTf) {
            $tfidf = [];
            foreach ($vocab as $term) {
                $tfidf[$term] = $docTf[$term] * $idf[$term];
            }
            $tfidfDocs[] = $tfidf;
        }
        return $tfidfDocs;
    }
}
