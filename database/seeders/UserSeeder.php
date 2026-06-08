<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fixed admin account
        $admin = User::factory()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@shop.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Fixed customer account for easy testing
        $customer = User::factory()->create([
            'name'     => 'Test Customer',
            'email'    => 'customer@shop.com',
            'password' => bcrypt('password'),
            'role'     => 'customer',
        ]);

        Address::factory()->create([
            'user_id'    => $customer->id,
            'is_default' => true,
        ]);

        // 10 random customers with addresses
        User::factory(10)->create(['role' => 'customer'])->each(function ($user) {
            Address::factory()->create([
                'user_id'    => $user->id,
                'is_default' => true,
            ]);
        });
    }
}
