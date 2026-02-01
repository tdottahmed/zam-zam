<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing profit margin settings to avoid duplicates if re-seeding without fresh
        SystemSetting::where('group', 'profit_margin')->delete();

        SystemSetting::create([
            'group' => 'profit_margin',
            'key' => 'default_profit_margin',
            'label' => 'Default Profit Margin',
            'value' => 10.00,
            'is_active' => true,
        ]);
    }
}
