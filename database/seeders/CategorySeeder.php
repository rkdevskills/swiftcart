<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics' => ['Phones', 'Laptops', 'Accessories'],
            'Clothing'    => ['Men', 'Women', 'Kids'],
            'Home'        => ['Furniture', 'Kitchen', 'Decor'],
            'Sports'      => ['Outdoor', 'Gym', 'Cycling'],
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = Category::create([
                'name'      => $parent,
                'slug'      => Str::slug($parent),
                'is_active' => true,
            ]);

            foreach ($children as $child) {
                Category::create([
                    'parent_id' => $parentCategory->id,
                    'name'      => $child,
                    'slug'      => Str::slug($parent . '-' . $child),
                    'is_active' => true,
                ]);
            }
        }
    }
}
