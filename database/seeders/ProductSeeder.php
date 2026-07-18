<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Categories
        $categories = [
            ['name' => 'Men', 'slug' => 'men', 'description' => 'Premium luxury apparel for men'],
            ['name' => 'Women', 'slug' => 'women', 'description' => 'Elegant designer apparel for women'],
            ['name' => 'Kids', 'slug' => 'kids', 'description' => 'Comfortable luxury apparel for children'],
        ];
        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['slug']] = Category::create($cat);
        }

        // 2. Seed Collections
        $collections = [
            ['name' => 'Summer Luxe', 'slug' => 'summer-luxe', 'description' => 'Lightweight high-end garments for hot weather'],
            ['name' => 'Winter Glam', 'slug' => 'winter-glam', 'description' => 'Warm knitwear and outerwear'],
            ['name' => 'Heritage Classic', 'slug' => 'heritage-classic', 'description' => 'Timeless minimal essentials'],
        ];
        $collectionModels = [];
        foreach ($collections as $col) {
            $collectionModels[$col['slug']] = Collection::create($col);
        }

        // 3. Seed Brands
        $brands = [
            ['name' => 'AUA Premium', 'slug' => 'aua-premium', 'description' => 'Ultra high-end bespoke garments'],
            ['name' => 'AUA Black Label', 'slug' => 'aua-black-label', 'description' => 'Contemporary luxury streetwear'],
            ['name' => 'AUA Sport', 'slug' => 'aua-sport', 'description' => 'High performance designer activewear'],
        ];
        $brandModels = [];
        foreach ($brands as $brand) {
            $brandModels[$brand['slug']] = Brand::create($brand);
        }

        // 4. Seed Sizes
        $sizes = [
            ['name' => 'Small', 'code' => 'S'],
            ['name' => 'Medium', 'code' => 'M'],
            ['name' => 'Large', 'code' => 'L'],
            ['name' => 'Extra Large', 'code' => 'XL'],
        ];
        $sizeModels = [];
        foreach ($sizes as $sz) {
            $sizeModels[$sz['code']] = Size::create($sz);
        }

        // 5. Seed Colors
        $colors = [
            ['name' => 'Charcoal Black', 'code' => '#1C1C1E'],
            ['name' => 'Ivory White', 'code' => '#F5F5F7'],
            ['name' => 'Imperial Gold', 'code' => '#D4AF37'],
            ['name' => 'Royal Blue', 'code' => '#1E3A8A'],
            ['name' => 'Emerald Green', 'code' => '#064E3B'],
        ];
        $colorModels = [];
        foreach ($colors as $col) {
            $colorModels[$col['name']] = Color::create($col);
        }

        // 6. Seed Products
        $productsData = [
            [
                'name' => 'Premium Oversized Hoodie',
                'slug' => 'premium-oversized-hoodie',
                'category' => 'men',
                'collection' => 'summer-luxe',
                'brand' => 'aua-black-label',
                'price' => 8500.00,
                'discount_price' => 6800.00,
                'SKU' => 'AUA-HD-001',
                'short_description' => 'Crafted from heavyweight French Terry cotton, featuring dropshoulder design and minimal gold embroidered detailing.',
                'description' => 'This Premium Oversized Hoodie embodies luxury lounging. Made from 450GSM organic cotton French Terry, it provides an ultra-soft feel and structured drape. Features include double-lined hood, no drawstrings for a clean minimal front look, side seam pockets, and a subtle AUA embroidered logo in signature gold thread on the wrist. Designed for unisex wear.',
                'stock' => 50,
                'featured' => true,
                'new_arrival' => true,
                'sale_product' => true,
                'variants' => [
                    ['color' => 'Charcoal Black', 'size' => 'M', 'stock' => 15, 'price_offset' => 0.00],
                    ['color' => 'Charcoal Black', 'size' => 'L', 'stock' => 10, 'price_offset' => 0.00],
                    ['color' => 'Ivory White', 'size' => 'M', 'stock' => 15, 'price_offset' => 0.00],
                    ['color' => 'Ivory White', 'size' => 'L', 'stock' => 10, 'price_offset' => 0.00],
                ]
            ],
            [
                'name' => 'Luxury Silk Slip Dress',
                'slug' => 'luxury-silk-slip-dress',
                'category' => 'women',
                'collection' => 'summer-luxe',
                'brand' => 'aua-premium',
                'price' => 18000.00,
                'discount_price' => null,
                'SKU' => 'AUA-DR-002',
                'short_description' => '100% pure Mulberry silk cowl neck dress with adjustable crisscross straps and dynamic drape.',
                'description' => 'Made from premium grade 22mm Mulberry silk, this slip dress falls gracefully against the body, showcasing a cowl neckline and low-cut back. The double lining ensures full coverage, while the adjustable back ties allow a customized fit. Perfect for high-society evening events or casual luxury lunches.',
                'stock' => 25,
                'featured' => true,
                'new_arrival' => true,
                'sale_product' => false,
                'variants' => [
                    ['color' => 'Imperial Gold', 'size' => 'S', 'stock' => 5, 'price_offset' => 2000.00],
                    ['color' => 'Imperial Gold', 'size' => 'M', 'stock' => 5, 'price_offset' => 2000.00],
                    ['color' => 'Charcoal Black', 'size' => 'S', 'stock' => 5, 'price_offset' => 0.00],
                    ['color' => 'Charcoal Black', 'size' => 'M', 'stock' => 10, 'price_offset' => 0.00],
                ]
            ],
            [
                'name' => 'Kids Cashmere Knit Sweater',
                'slug' => 'kids-cashmere-sweater',
                'category' => 'kids',
                'collection' => 'winter-glam',
                'brand' => 'aua-premium',
                'price' => 12000.00,
                'discount_price' => 9500.00,
                'SKU' => 'AUA-SW-003',
                'short_description' => 'Pure Mongolian cashmere round-neck sweater, soft and skin-friendly for young ones.',
                'description' => 'Give your children the ultimate comfort with our cashmere sweater. Made of 100% fine Mongolian cashmere, it is incredibly soft and warm without being bulky. Features rib-knit crewneck, cuffs, and hem. Resistant to pilling and fully machine-washable on wool-cycle.',
                'stock' => 30,
                'featured' => false,
                'new_arrival' => true,
                'sale_product' => true,
                'variants' => [
                    ['color' => 'Ivory White', 'size' => 'S', 'stock' => 15, 'price_offset' => 0.00],
                    ['color' => 'Ivory White', 'size' => 'M', 'stock' => 15, 'price_offset' => 0.00],
                ]
            ],
            [
                'name' => 'Minimalist Pima Cotton Polo',
                'slug' => 'minimalist-pima-polo',
                'category' => 'men',
                'collection' => 'heritage-classic',
                'brand' => 'aua-sport',
                'price' => 6500.00,
                'discount_price' => null,
                'SKU' => 'AUA-PL-004',
                'short_description' => 'Premium Pima cotton polo shirt, slim fit with structured collar and mother-of-pearl buttons.',
                'description' => 'Our classic minimalist polo is constructed from long-staple Peruvian Pima cotton mesh. It features a modern slim-fit silhouette, a reinforced ribbed collar that maintains its shape, and genuine mother-of-pearl buttons. Breathable, durable, and exceptionally soft.',
                'stock' => 60,
                'featured' => true,
                'new_arrival' => false,
                'sale_product' => false,
                'variants' => [
                    ['color' => 'Charcoal Black', 'size' => 'M', 'stock' => 15, 'price_offset' => 0.00],
                    ['color' => 'Charcoal Black', 'size' => 'L', 'stock' => 15, 'price_offset' => 0.00],
                    ['color' => 'Royal Blue', 'size' => 'M', 'stock' => 15, 'price_offset' => 0.00],
                    ['color' => 'Royal Blue', 'size' => 'L', 'stock' => 15, 'price_offset' => 0.00],
                ]
            ],
            [
                'name' => 'Premium Cropped Trench Coat',
                'slug' => 'premium-cropped-trench-coat',
                'category' => 'women',
                'collection' => 'winter-glam',
                'brand' => 'aua-premium',
                'price' => 24000.00,
                'discount_price' => 21000.00,
                'SKU' => 'AUA-CO-005',
                'short_description' => 'Water-resistant gabardine double-breasted cropped trench coat with custom buckle details.',
                'description' => 'A chic, modern reinterpretation of the classic trench coat. Featuring a cropped waist-length design, double-breasted closure, epaulettes, and leather-bound buckle wrist straps. Crafted from premium Japanese cotton gabardine, water-resistant and lined with custom silk satin.',
                'stock' => 15,
                'featured' => true,
                'new_arrival' => true,
                'sale_product' => true,
                'variants' => [
                    ['color' => 'Imperial Gold', 'size' => 'S', 'stock' => 3, 'price_offset' => 1000.00],
                    ['color' => 'Imperial Gold', 'size' => 'M', 'stock' => 5, 'price_offset' => 1000.00],
                    ['color' => 'Emerald Green', 'size' => 'M', 'stock' => 7, 'price_offset' => 0.00],
                ]
            ]
        ];

        foreach ($productsData as $data) {
            $product = Product::create([
                'category_id' => $categoryModels[$data['category']]->id,
                'collection_id' => $collectionModels[$data['collection']]->id,
                'brand_id' => $brandModels[$data['brand']]->id,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'discount_price' => $data['discount_price'],
                'SKU' => $data['SKU'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'stock' => $data['stock'],
                'featured' => $data['featured'],
                'new_arrival' => $data['new_arrival'],
                'sale_product' => $data['sale_product'],
                'status' => true,
                'meta_title' => $data['name'] . ' | AUA Collection',
                'meta_description' => $data['short_description'],
            ]);

            // Add standard placeholder image
            // We'll use mock local paths that can fall back to nice CSS placeholders in views
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'assets/images/products/' . $product->slug . '-1.jpg',
                'is_primary' => true
            ]);
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'assets/images/products/' . $product->slug . '-2.jpg',
                'is_primary' => false
            ]);

            // Add Variants
            foreach ($data['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $colorModels[$v['color']]->id ?? null,
                    'size_id' => $sizeModels[$v['size']]->id ?? null,
                    'stock' => $v['stock'],
                    'additional_price' => $v['price_offset'],
                    'SKU' => $product->SKU . '-' . ($sizeModels[$v['size']]->code ?? '') . '-' . strtoupper(substr($v['color'], 0, 2)),
                ]);
            }
        }
    }
}
