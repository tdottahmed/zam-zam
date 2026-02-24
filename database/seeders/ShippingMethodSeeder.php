<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingMethod;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Company Shipment',
                'description' => 'Shipped via our company delivery fleet.',
                'cost' => 0.00,
                'is_active' => true,
            ],
            [
                'name' => 'Personal Shipment',
                'description' => 'Customer will arrange personal pickup or shipping.',
                'cost' => null,
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            ShippingMethod::updateOrCreate(
                ['name' => $method['name']],
                $method
            );
        }
    }
}
