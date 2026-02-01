<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Kilogram', 'code' => 'kg'],
            ['name' => 'Gram', 'code' => 'gm'],
            ['name' => 'Liter', 'code' => 'ltr'],
            ['name' => 'Milliliter', 'code' => 'ml'],
            ['name' => 'Piece', 'code' => 'pc'],
            ['name' => 'Dozen', 'code' => 'doz'],
            ['name' => 'Box', 'code' => 'box'],
            ['name' => 'Carton', 'code' => 'ctn'],
            ['name' => 'Packet', 'code' => 'pkt'],
            ['name' => 'Bag', 'code' => 'bag'],
        ];

        foreach ($units as $unit) {
            Unit::create(array_merge($unit, ['is_active' => true]));
        }
    }
}
