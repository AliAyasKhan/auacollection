<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'SUMMER LUXE 2026',
                'subtitle' => 'Elegance in the heat. Discover Mulberry silk slip dresses and linen structures.',
                'image_path' => 'assets/images/banners/summer_luxe.jpg',
                'link' => '/shop?collection=summer-luxe',
                'button_text' => 'DISCOVER NOW',
                'order' => 1,
                'status' => true,
            ],
            [
                'title' => 'HERITAGE ESSENTIALS',
                'subtitle' => 'Classic cuts, organic materials, and minimalist designs engineered to last.',
                'image_path' => 'assets/images/banners/heritage.jpg',
                'link' => '/shop?collection=heritage-classic',
                'button_text' => 'BROWSE COLLECTION',
                'order' => 2,
                'status' => true,
            ],
            [
                'title' => 'THE BLACK LABEL',
                'subtitle' => 'Premium oversized outerwear and heavyweight French Terry basics.',
                'image_path' => 'assets/images/banners/black_label.jpg',
                'link' => '/shop?brand=aua-black-label',
                'button_text' => 'SHOP THE STYLES',
                'order' => 3,
                'status' => true,
            ]
        ];

        foreach ($banners as $b) {
            Banner::create($b);
        }
    }
}
