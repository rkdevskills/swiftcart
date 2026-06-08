<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $childCategories = Category::whereNotNull('parent_id')->get();
        $customers       = User::where('role', 'customer')->get();

        foreach ($childCategories as $category) {
            // 5 products per child category
            Product::factory(5)->create(['category_id' => $category->id])->each(function ($product) use ($customers) {

                // Primary image
                ProductImage::factory()->create([
                    'product_id' => $product->id,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);

                // 2 extra images
                ProductImage::factory(2)->create([
                    'product_id' => $product->id,
                ]);

                // 3 reviews per product
                $customers->random(3)->each(function ($user) use ($product) {
                    Review::factory()->create([
                        'user_id'    => $user->id,
                        'product_id' => $product->id,
                    ]);
                });
            });
        }
    }
}
