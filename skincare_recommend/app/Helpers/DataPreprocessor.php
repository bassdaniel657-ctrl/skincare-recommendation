<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class DataPreprocessor
{
  protected static $protectedTerms = [
    '1,2-hexanediol' => '1,2-hexanediol',
    'hyaluronic acid' => 'hyaluronic acid',
    'sodium hyaluronate' => 'sodium hyaluronate',
    'asiatic acid' => 'asiatic acid',
    // Tambahkan lainnya jika ada istilah lain yang perlu dilindungi
  ];

  protected static $synonymMap = [];

  // Muat sinonim dari CSV (Kata Utama, Sinonim)
  public static function loadSynonyms($path = 'synonim.csv')
  {
    if (!empty(self::$synonymMap)) return;

    $file = Storage::get($path);
    $rows = array_map('str_getcsv', explode("\n", $file));

    foreach ($rows as $row) {
      if (count($row) < 2) continue;
      [$mainTerm, $synonym] = $row;

      $mainTerm = strtolower(trim($mainTerm));
      $synonym = strtolower(trim($synonym));

      // Jika keduanya identik, lewati (hindari pengulangan)
      if ($mainTerm === $synonym) continue;

      // Normalisasi main term
      $normalized = (str_word_count($mainTerm) > 1) ? str_replace(' ', '|', $mainTerm) : $mainTerm;
      $normalized = $mainTerm;

      self::$synonymMap[$synonym] = $normalized;
    }
  }



  public static function normalizeSynonyms($text)
  {
    // Urutkan sinonim berdasarkan panjang karakter menurun (untuk proses frasa panjang dulu)
    $synonyms = array_keys(self::$synonymMap);
    usort($synonyms, function ($a, $b) {
      return strlen($b) - strlen($a);
    });

    foreach ($synonyms as $synonym) {
      $normalized = self::$synonymMap[$synonym];

      // Gunakan boundary yang aman agar tidak ganti sebagian kata
      $pattern = '/\b' . preg_quote($synonym, '/') . '\b/';
      $text = preg_replace($pattern, $normalized, $text);
    }

    return $text;
  }


  // Fungsi utama preprocessing
  public static function preprocess($text)
  {
    // 1. Lowercasing
    $text = strtolower($text);

    // 2. Lindungi istilah khusus (contoh 1,2-hexanediol)
    foreach (self::$protectedTerms as $orig => $repl) {
      // Protect the term by replacing it with a placeholder
      $text = str_replace($orig, '__' . $orig . '__', $text);
    }

    // dump($text);

    // 3. Load & Apply Normalisasi Sinonim **sebelum** hapus karakter
    self::loadSynonyms();
    $text = self::normalizeSynonyms($text);

    // 4. Hapus karakter selain huruf, angka, '-', '|', dan spasi
    $text = preg_replace('/[^a-z0-9|\-,\s]/', '', $text);  // Allow hyphen (-) and comma (,) to stay

    // 5. Bersihkan spasi ganda
    $text = preg_replace('/\s+/', ' ', $text);

    // 6. Menghapus kata berulang
    $words = explode(' ', $text);          // Memecah teks menjadi array kata
    $words = array_unique($words);         // Menghapus duplikasi kata
    $text = implode(' ', $words);          // Menggabungkan kembali menjadi string

    // 7. Kembalikan istilah yang dilindungi
    foreach (self::$protectedTerms as $orig => $repl) {
      $text = str_replace('__' . $orig . '__', $orig, $text);
    }

    return trim($text);
  }
}
