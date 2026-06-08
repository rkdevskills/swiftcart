<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected Category $category;
    /**
     * A basic feature test example.
     */
    // ── Setup ─────────────────────────────────────────
    protected function setUp(): void
    {
        parent::setUp();

        // Create a category for all tests in this file
        $this->category = Category::factory()->create();
    }

    // ── Test 1 ────────────────────────────────────────
    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    // ── Test 2 ────────────────────────────────────────
    public function test_products_page_shows_active_products()
    {
        // Arrange
        $activeProduct   = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active'   => true,
            'stock'       => 10,
        ]);

        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active'   => false,
            'stock'       => 10,
        ]);

        // Act
        $response = $this->get('/products');

        // Assert
        $response->assertOk();
        $response->assertSee($activeProduct->name);
        $response->assertDontSee($inactiveProduct->name);
    }

    // ── Test 3 ────────────────────────────────────────
    public function test_product_detail_page_loads()
    {
        // Arrange
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active'   => true,
        ]);

        // Act
        $response = $this->get("/products/{$product->slug}");

        // Assert
        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_product_detail_page_shows_404_for_inactive_product()
    {
        // Arrange
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active'   => false,
        ]);

        // Act
        $response = $this->get("/products/{$product->slug}");

        // Assert
        $response->assertNotFound();
    }

    public function test_out_of_stock_product_page_still_loads()
    {
        // Arrange
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active'   => true,
            'stock'       => 0,
        ]);

        // Act
        $response = $this->get("/products/{$product->slug}");

        // Assert
        $response->assertOk();
        $response->assertSee($product->name);
        $response->assertSee('Out of Stock');
    }

    public function test_product_search_returns_relevant_results()
    {
        // Arrange
        $matchingProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name'        => 'Unique Product Name',
            'is_active'   => true,
        ]);

        $nonMatchingProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'name'        => 'Another Product',
            'is_active'   => true,
        ]);

        // Act
        $response = $this->get('/products?search=Unique');

        // Assert
        $response->assertOk();
        $response->assertSee($matchingProduct->name);
        $response->assertDontSee($nonMatchingProduct->name);
    }

}
