<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Setup Placeholder
        $placeholderPath = storage_path('app/public/placeholders');
        if (!file_exists($placeholderPath)) {
             mkdir($placeholderPath, 0755, true);
        }
        copy(public_path('images/logo.jpg'), $placeholderPath . '/logo.jpg');

        // Create Admin User if not exists
        if (!User::where('email', 'admin@admin.com')->exists()) {
             User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
            ]);
        }
        
        \App\Models\Category::factory(10)->create();
        \App\Models\Brand::factory(10)->create();
        \App\Models\Product::factory(50)->create();
    }
}
