<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductType;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/seeds/products.xlsx');

        if (!file_exists($path)) {
            $this->command?->error("❌ Az Excel fájl nem található: {$path}");
            return;
        }

        // XLSX beolvasás
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();

        // returnCellRef = false → nem 'A','B','C' kulcsok, hanem sima indexek
        $rows = $sheet->toArray(null, true, true, false);

        if (empty($rows) || empty($rows[0])) {
            $this->command?->error('❌ Az Excel üresnek tűnik vagy nincs fejléc.');
            return;
        }

        // Fejléc (első sor)
        $headers = array_map(fn($h) => strtolower(trim((string)$h)), $rows[0]);

        // Kötelező mezők ellenőrzése
        $required = ['name', 'description', 'price', 'image', 'product_type_slug'];
        $missing = array_diff($required, $headers);
        if (!empty($missing)) {
            $this->command?->error('❌ Hiányzó fejléc(ek): ' . implode(', ', $missing));
            return;
        }

        // Oszlop indexek gyors lookup-hoz
        $idx = array_flip($headers);

        $countOk = 0;
        $countSkip = 0;

        // Adatsorok: a 2. sortól (index 1)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            // Üres sorok átugrása
            if (!isset($row[$idx['name']]) || trim((string)$row[$idx['name']]) === '') {
                $countSkip++;
                continue;
            }

            $name   = trim((string)($row[$idx['name']] ?? ''));
            $desc   = (string)($row[$idx['description']] ?? '');
            $image  = (string)($row[$idx['image']] ?? '');
            $ptype  = trim((string)($row[$idx['product_type_slug']] ?? ''));
            $priceRaw = (string)($row[$idx['price']] ?? '0');
            // Ár normalizálása → szám kinyerése
            $priceHUF = (float) str_replace([' ', '.', ','], '', $priceRaw);

            // HUF → EUR konverzió (pl.: 28 990 Ft → 28990 / 381 ≈ 76.07 €)
            $price = round($priceHUF / 381, 2);


            // Típus feloldása
            $productTypeId = null;
            if ($ptype !== '') {
                $productTypeId = ProductType::where('slug', $ptype)->value('id');
                if (!$productTypeId) {
                    $this->command?->warn("⚠️  (sor ".($i+1).") Ismeretlen product_type_slug: '{$ptype}' – a termék felvéve típus nélkül: {$name}");
                }
            } else {
                $this->command?->warn("⚠️  (sor ".($i+1).") Üres product_type_slug – a termék felvéve típus nélkül: {$name}");
            }

            // Idempotens mentés név alapján
            Product::updateOrCreate(
                ['name' => $name],
                [
                    'description'     => $desc,
                    'price'           => $price,
                    'image'           => $image,
                    'product_type_id' => $productTypeId,
                ]
            );

            $countOk++;
        }

        $this->command?->info("✅ Import kész. Sikeres sorok: {$countOk}, kihagyott sorok: {$countSkip}");
    }
}
