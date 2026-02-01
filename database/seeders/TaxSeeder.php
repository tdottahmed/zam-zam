<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            [
                'name' => 'No Tax',
                'value' => 0.00,
                'is_active' => true,
            ],
            [
                'name' => 'VAT 5%',
                'value' => 5.00,
                'is_active' => true,
            ],
            [
                'name' => 'VAT 10%',
                'value' => 10.00,
                'is_active' => true,
            ],
            [
                'name' => 'VAT 15%',
                'value' => 15.00,
                'is_active' => true,
            ],
        ];

        foreach ($taxes as $tax) {
            Tax::create($tax);
        }
    }
}
