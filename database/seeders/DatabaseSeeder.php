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
        $this->call(UserSeeder::class);
        $placeholderPath = storage_path('app/public/placeholders');
        if (!file_exists($placeholderPath)) {
             mkdir($placeholderPath, 0755, true);
        }
        copy(public_path('images/logo.jpg'), $placeholderPath . '/logo.jpg');

        $this->call([
            AdminSeeder::class,
            TaxSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            UnitSeeder::class,
            // ProductSeeder::class,
            SystemSettingSeeder::class,
        ]);
    }
}
