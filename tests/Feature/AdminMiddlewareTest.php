<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Category;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;

    public function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Create a regular user
        $this->customer = User::factory()->create([
            'role' => 'customer',
        ]);
    }

    // ── Test 1 ────────────────────────────────────────
    public function test_guest_cannot_access_admin_panel()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    // ── Test 2 ────────────────────────────────────────
    public function test_customer_cannot_access_admin_panel()
    {
        $response = $this->actingAs($this->customer)->get('/admin');
        $response->assertForbidden();
    }

    // ── Test 3 ────────────────────────────────────────
    public function test_admin_can_access_admin_panel()
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertOk();
    }

    public function test_customer_cannot_access_admin_categories()
    {
        $response = $this->actingAs($this->customer)->get('/admin/categories');
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_categories()
    {
        $response = $this->actingAs($this->admin)->get('/admin/categories');
        $response->assertOk();
    }

    public function test_customer_cannot_access_admin_products()
    {
        $response = $this->actingAs($this->customer)->get('/admin/products');
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_products()
    {
        $response = $this->actingAs($this->admin)->get('/admin/products');
        $response->assertOk();
    }

    public function test_customer_cannot_access_admin_orders()
    {
        $response = $this->actingAs($this->customer)->get('/admin/orders');
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_orders()
    {
        $response = $this->actingAs($this->admin)->get('/admin/orders');
        $response->assertOk();
    }

    public function test_admin_can_create_category()
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'New Category',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
            'slug' => 'new-category',
            'is_active' => true,
        ]);
    }

    public function test_customer_cannot_create_category()
    {
        $response = $this->actingAs($this->customer)->post('/admin/categories', [
            'name' => 'Fake Category',
            'is_active' => true,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('categories', [
            'name' => 'Fake Category',
        ]);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/categories/{$category->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_customer_cannot_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->customer)->delete("/admin/categories/{$category->id}");
        $response->assertForbidden();
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_customer_cannot_create_product()
    {
        $productData = [
            'name' => 'Test Product',
            'category_id' => Category::factory()->create()->id,
            'price' => 99.99,
            'stock' => 10,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->customer)->post('/admin/products', $productData);
        $response->assertForbidden();
        $this->assertDatabaseMissing('products', [
            'name' => 'Test Product',
        ]);
    }

    public function test_admin_can_create_product()
    {
        $productData = [
            'name' => 'Test Product',
            'category_id' => Category::factory()->create()->id,
            'price' => 99.99,
            'stock' => 10,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/products', $productData);
        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
        ]);
    }

}