<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('slug');
            }
        });

        // Backfill from primary product_images rows
        if (Schema::hasTable('product_images')) {
            $primaries = DB::table('product_images')
                ->where('is_primary', true)
                ->get(['product_id', 'image_path']);

            foreach ($primaries as $row) {
                DB::table('products')
                    ->where('id', $row->product_id)
                    ->update(['image' => $row->image_path]);
            }

            // Products with images but no primary
            $productsWithoutImage = DB::table('products')
                ->whereNull('image')
                ->pluck('id');

            foreach ($productsWithoutImage as $productId) {
                $path = DB::table('product_images')
                    ->where('product_id', $productId)
                    ->value('image_path');

                if ($path) {
                    DB::table('products')->where('id', $productId)->update(['image' => $path]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
