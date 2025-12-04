<?php

namespace App\Services;

class SimilarityService
{
    public static function cosine(array $queryVector, array $docVector): float
    {
        $dot = 0; 
        $qMag = 0; 
        $dMag = 0;
        
        foreach ($queryVector as $term => $qValue) {
            $dValue = $docVector[$term] ?? 0;
            $dot += $qValue * $dValue;
            $qMag += pow($qValue, 2);
            $dMag += pow($dValue, 2);
        }

        $denom = sqrt($qMag) * sqrt($dMag);
        return $denom ? $dot / $denom : 0.0;
    }

    public static function euclidean(array $queryVector, array $docVector): float
    {
        $sum = 0;
        foreach ($queryVector as $term => $qValue) {
            $dValue = $docVector[$term] ?? 0;
            $sum += pow($dValue - $qValue, 2);
        }
        $dist = sqrt($sum);
        return $dist;
    }
}
