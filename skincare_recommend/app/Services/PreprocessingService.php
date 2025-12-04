<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PreprocessingService
{
    protected static $synonymMap = [];
    protected static $specialIngredients = [
        'blackberry',
        'tea',
        'lemon',
        'cucumber',
        'rose',
        'rosemary',
        'chamomile',
        'lavender',
        'aloe',
        'honey',
        'oat',
        'cocoa',
        'shea',
        'rice',
        'carrot',
        'coffee',
        'mint',
        'grape',
        'vanilla',
        'papaya'
    ];

    // Muat sinonim dari CSV
    public static function loadSynonyms($path = 'sinonim.csv')
    {
        if (!empty(self::$synonymMap)) return;

        $file = Storage::get($path);
        $rows = array_map('str_getcsv', explode("\n", $file));

        foreach ($rows as $row) {
            if (count($row) < 2) continue;

            [$main, $syn] = $row;

            $main = strtolower(trim($main));
            $syn  = strtolower(trim($syn));

            if ($main === '' || $syn === '') continue;

            // Multi-word ingredient → ubah menjadi format “kata1|kata2”
            $normMain = str_replace(' ', '|', $main);
            $normSyn  = str_replace(' ', '|', $syn);

            self::$synonymMap[$normSyn] = $normMain;
        }
    }

    // Tokenizer: hanya pisahkan berdasarkan '|'
    public static function tokenize($text): array
    {
        if (is_array($text)) {
            return array_values(array_filter(array_map('trim', $text)));
        }

        $parts = explode('|', $text);

        $parts = array_map('trim', $parts);
        $parts = array_filter($parts, fn($t) => $t !== '' && $t !== null);

        // Tidak boleh memecah dengan spasi!
        return array_values(array_unique($parts));
    }

    // Normalisasi sinonim
    protected static function normalizeSynonyms($text)
    {
        // Sort syn key oleh panjang — frasa panjang dulu
        $syns = array_keys(self::$synonymMap);
        usort($syns, fn($a, $b) => strlen($b) <=> strlen($a));

        foreach ($syns as $syn) {
            $replacement = self::$synonymMap[$syn];
            $pattern = '/' . preg_quote($syn, '/') . '/i';
            $text = preg_replace($pattern, $replacement, $text);
        }
        return $text;
    }

    // Preprocess final
    public static function preprocess($text)
    {
        // lowercase
        $text = strtolower($text);
        
        // load synonym map
        self::loadSynonyms();
        
        // apply synonym normalization (mapping term|term)
        $text = self::normalizeSynonyms($text);

        // dump($text);

        $replacedText = str_replace(['/', '(', ')'], '', $text);
        $replacedText = preg_replace_callback(
            '/[a-z0-9]+(?:\s+[a-z0-9]+)+/',
            function ($m) {
                return str_replace(' ', '|', $m[0]);
            },
            $replacedText
        );

        $text = explode('|', $text);

        $buffer = [];
        foreach(self::$specialIngredients as $ingredient){
            if(str_contains($replacedText, $ingredient)){
                $buffer[] = $ingredient;
            }
        }
        // $replacedText = explode('|', $replacedText);
        $text = array_merge($text, $buffer);
        $text = array_unique($text);
        $text = implode('|', $text);
        // dump($text);

        // hapus karakter aneh kecuali huruf/angka/pipe
        $text = preg_replace('/[^a-z0-9|]/', ' ', $text);

        // rapikan spasi
        $text = preg_replace('/\s+/', ' ', $text);

        // tidak boleh hapus duplikat di sini! (menghapus duplikat merusak TF)
        return trim($text);
    }
}
