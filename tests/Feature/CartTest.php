<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected User $customer;
    protected Product $product;
    
    // ── Setup ─────────────────────────────────────────
    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();

        $this->customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active'   => true,
            'stock'       => 10,
            'price'       => 100.00,
        ]);
    }

    // ── Test 1 ────────────────────────────────────────
    public function test_guest_cannot_access_cart()
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
    }

    // ── Test 2 ────────────────────────────────────────
    public function test_customer_can_view_empty_cart()
    {
        $response = $this->actingAs($this->customer)->get('/cart');
        $response->assertStatus(200);
    }

    // ── Test 3 ────────────────────────────────────────
    public function test_customer_can_add_product_to_cart()
    {
        $response = $this->actingAs($this->customer)->post('/cart', [
            'product_id' => $this->product->id,
            'quantity'   => 2,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('cart_items', [
            'user_id'    => $this->customer->id,
            'product_id' => $this->product->id,
            'quantity'   => 2,
        ]);
    }

        // ── Test 4 ────────────────────────────────────────
        public function test_guest_cannot_add_product_to_cart()
        {
            $response = $this->post('/cart', [
                'product_id' => $this->product->id,
                'quantity'   => 2,
            ]);

            $response->assertRedirect('/login');

            $this->assertDatabaseMissing('cart_items', [
                'product_id' => $this->product->id,
                'quantity'   => 2,
            ]);
        }

        // ── Test 5 ────────────────────────────────────────
        public function test_customer_cannot_add_out_of_stock_product_to_cart()
        {
            $this->product->update(['stock' => 0]);

            $response = $this->actingAs($this->customer)->post('/cart', [
                'product_id' => $this->product->id,
                'quantity'   => 1,
            ]);

            $this->assertDatabaseMissing('cart_items', [
                'product_id' => $this->product->id,
                'quantity'   => 1,
            ]);
        }

            // ── Test 6 ────────────────────────────────────────
            public function test_customer_can_remove_item_from_cart()
            {
                // Arrange — add item to cart first
                $this->actingAs($this->customer)
                    ->post('/cart', [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                    ]);

                $cartItem = $this->customer->cartItems()->first();

                // Act — remove it
                $response = $this->actingAs($this->customer)
                                ->delete("/cart/{$cartItem->id}");

                // Assert — gone from database
                $response->assertRedirect();
                $this->assertDatabaseMissing('cart_items', [
                    'id' => $cartItem->id,
                ]);
            }

            // ── Test 7 ────────────────────────────────────────
            public function test_customer_can_not_remove_other_users_cart_item()
            {
                // Arrange — create another user and add item to their cart
                $otherUser = User::factory()->create(['role' => 'customer']);
                $this->actingAs($otherUser)
                    ->post('/cart', [
                        'product_id' => $this->product->id,
                        'quantity'   => 1,
                    ]);

                $cartItem = $otherUser->cartItems()->first();

                // Act — try to delete other user's cart item
                $response = $this->actingAs($this->customer)
                                ->delete("/cart/{$cartItem->id}");

                // Assert — still in database, and we get 404 (not found)
                $response->assertForbidden();
                $this->assertDatabaseHas('cart_items', [
                    'id' => $cartItem->id,
                ]);
            }
}