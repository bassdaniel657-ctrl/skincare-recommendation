<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
  public function run(): void
  {
    $csvPath = storage_path('app/produk.csv');
    $csv = array_map(function ($line) {
      return str_getcsv($line, ';');
    }, file($csvPath));


    // Ambil header
    $header = array_map('trim', $csv[0]);
    $expectedColumns = count($header);

    if (!file_exists($csvPath)) {
      $this->command->error("File tidak ditemukan di path: $csvPath");
      return;
    }

    $this->command->info("Memproses file: $csvPath");
    $this->command->info("Jumlah baris: " . count($csv));


    foreach (array_slice($csv, 1) as $row) {
      // Lewati baris yang jumlah kolomnya tidak sesuai
      if (count($row) !== $expectedColumns) {
        $this->command->warn("Baris dilewati (jumlah kolom tidak sesuai): " . json_encode($row));
        continue;
      }

      $data = array_combine($header, $row);

      DB::table('products')->insert([
        'product_name' => $data['product_name'],
        'brand_name' => $data['brand_name'] ?? null,
        'details' => null,
        'ingredients' => $data['ingredients'],
        'price' => (int) ($data['price'] ?? 0),
        'category' => $data['category'] ?? null,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }
}
