<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Address;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected user $customer;
    protected user $admin;
    protected product $product;
    protected category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create(['role' => 'customer']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active'   => true,
            'stock'       => 10,
            'price'       => 100.00,
            ]);
    }

    //--- Helper method to create an order for testing reviews ---//
    protected function createOrderForCustomer(User $customer, Product $product) : Order
    {
        $address = Address::factory()->create(['user_id' => $customer->id]);

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'address_id' => $address->id,
            'status' => 'delivered',
            'subtotal' => $product->price,
            'total' => $product->price,
            'shipping' => 0,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
        ]);

        Payment::factory()->create([
            'order_id' => $order->id,
            'provider' => 'stripe',
            'status' => 'paid',
            'amount' => $order->total,
        ]);

        return $order;
    }

    // ── Test 1 ────────────────────────────────────────
    public function test_guest_cannot_submit_review()
    {
        $response = $this->post("/products/{$this->product->id}/reviews", [
            'rating' => 5,
            'body'   => 'Great product!',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('reviews', [
            'product_id' => $this->product->id,
        ]);
    }

    // ── Test 2 ────────────────────────────────────────
    public function test_customer_cannot_review_without_purchase()
    {
        $response = $this->actingAs($this->customer)
                     ->post("/products/{$this->product->id}/reviews", [
                         'rating' => 5,
                         'body'   => 'Great product!',
                     ]);

        $response->assertRedirect(route('products.show', $this->product));
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('reviews', [
            'user_id'    => $this->customer->id,
            'product_id' => $this->product->id,
        ]);
    }

    // ── Test 3 ────────────────────────────────────────
    public function test_customer_can_review_purchased_product()
    {
        $this->createOrderForCustomer($this->customer, $this->product);

        $response = $this->actingAs($this->customer)->post("/products/{$this->product->id}/reviews", [
            'rating' => 5,
            'body'   => 'Excellent product! Highly recommend.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'rating' => 5,
            'body' => 'Excellent product! Highly recommend.',
            'is_approved' => false, // Should be pending approval
        ]);
    }

    // ── Test 4 ────────────────────────────────────────
    public function test_customer_cannot_review_same_product_twice()
    {
        $this->createOrderForCustomer($this->customer, $this->product);

        $this->actingAs($this->customer)->post("/products/{$this->product->id}/reviews", [
            'rating' => 5,
            'body'   => 'First review.',
        ]);

        $response = $this->actingAs($this->customer)->post("/products/{$this->product->id}/reviews", [
            'rating' => 4,
            'body'   => 'Second review attempt.',
        ]);

        $response->assertRedirect(route('products.show', $this->product));
        $response->assertSessionHas('error');

        $this->assertEquals(1, Review::where([
            'user_id'    => $this->customer->id,
            'product_id' => $this->product->id,
        ])->count());
    }
    
    // ── Test 5 ────────────────────────────────────────
    public function test_customer_can_delete_own_review()
    {
        $this->createOrderForCustomer($this->customer, $this->product);

        $review = Review::factory()->create([
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
        ]);

        $response = $this->actingAs($this->customer)->delete("/reviews/{$review->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    // ── Test 6 ────────────────────────────────────────
    public function test_customer_cannot_delete_others_review()
    {
        $anotherCustomer = User::factory()->create(['role' => 'customer']);

        $this->createOrderForCustomer($anotherCustomer, $this->product);

        $review = Review::create([
            'user_id'    => $anotherCustomer->id,
            'product_id' => $this->product->id,
            'rating'     => 5,
            'body'       => 'Great product!',
            'is_approved'=> false,
        ]);

        $response = $this->actingAs($this->customer)
                        ->delete("/reviews/{$review->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
        ]);
    }

     // ── Test 7 ────────────────────────────────────────
     public function test_admin_can_approve_review()
     {
         $review = Review::factory()->create([
             'product_id' => $this->product->id,
             'user_id' => $this->customer->id,
             'is_approved' => false,
         ]);

         $response = $this->actingAs($this->admin)->patch("/admin/reviews/{$review->id}/approve");

         $response->assertRedirect();
         $this->assertDatabaseHas('reviews', [
             'id' => $review->id,
             'is_approved' => true,
         ]);
     }

        // ── Test 8 ────────────────────────────────────────
        public function test_admin_can_delete_any_review()
        {
            $review = Review::factory()->create([
                'product_id' => $this->product->id,
                'user_id' => $this->customer->id,
            ]);

            $response = $this->actingAs($this->admin)->delete("/admin/reviews/{$review->id}");

            $response->assertRedirect();
            $this->assertDatabaseMissing('reviews', [
                'id' => $review->id,
            ]);
        }
}
