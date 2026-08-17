<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['/storage/test-test/1.jpg', 1, 'veg steam momo', 10, 200],
            ['/storage/test-test/2.jpg', 1, 'veg fry momo', 10, 300],
            ['/storage/test-test/3.jpg', 1, 'veg c momo', 10, 150],
            ['/storage/test-test/4.jpg', 2, 'chicken steam momo', 10, 250],
            ['/storage/test-test/5.jpg', 2, 'chicken fry momo', 10, 350],
            ['/storage/test-test/6.jpg', 2, 'chicken c momo', 10, 300],
            ['/storage/test-test/7.jpg', 5, 'coke', 100, 100],
            ['/storage/test-test/8.jpg', 6, 'pepsi', 100, 100],
            ['/storage/test-test/9.jpg', 7, 'fanta', 100, 100],
            ['/storage/test-test/10.jpg', 8, 'wine', 1, 2540],
            ['/storage/test-test/11.jpg', 9, 'vodka', 1, 3540],
            ['/storage/test-test/12.jpg', 10, 'rum', 1, 3650],
        ];

        foreach($data as $datum) {
            $pv = new ProductVariant();
            $pv->image_url = $datum[0];
            $pv->product_id = $datum[1];
            $pv->name = $datum[2];
            $pv->quantity = $datum[3];
            $pv->rate = $datum[4];
            $pv->save();

            $this->generatePlaceholderImage($datum[0], $datum[2]);
         }
    }

    /**
     * Create a placeholder image file for a seeded variant so its thumbnail
     * resolves instead of 404-ing (the seeder only stores a path, not a file).
     */
    private function generatePlaceholderImage(string $imageUrl, string $label): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            return; // GD not available; skip silently
        }
        $relative = ltrim(str_replace('/storage/', '', $imageUrl), '/');
        $path = storage_path('app/public/' . $relative);
        if (is_file($path)) {
            return;
        }
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }
        $img = imagecreatetruecolor(100, 100);
        $bg = imagecolorallocate($img, 59, 130, 246);
        $fg = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, 100, 100, $bg);
        imagestring($img, 3, 6, 44, substr($label, 0, 13), $fg);
        imagejpeg($img, $path, 85);
        imagedestroy($img);
    }
}
