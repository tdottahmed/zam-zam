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

        // General Settings
        SystemSetting::where('group', 'general')->delete();

        $generalSettings = [
            ['key' => 'site_name', 'label' => 'Site Name', 'value' => 'ZamZam Import and Export Inc.'],
            ['key' => 'contact_email', 'label' => 'Contact Email', 'value' => 'zamzamimport2023@gmail.com'],
            ['key' => 'contact_phone', 'label' => 'Contact Phone', 'value' => '+1 416-283-4488'],
            ['key' => 'contact_cell', 'label' => 'Contact Cell', 'value' => '+1 647-482-1133'],
            ['key' => 'tax_id', 'label' => 'Tax ID', 'value' => '731247144RT0001'],
            ['key' => 'address', 'label' => 'Address', 'value' => "1-283 Morningside Ave\nScarborough, Ontario, M1E 3G1\nCanada"],
            ['key' => 'currency_symbol', 'label' => 'Currency Symbol', 'value' => '$'],
            ['key' => 'address_alt', 'label' => 'Alternate Address', 'value' => "500 Coronation Drive, Unit-14\nScarborough Ontario-M1E4V7"],
            ['key' => 'contact_email_alt_1', 'label' => 'Alternate Email 1', 'value' => 'zamzamimport2023@gmail.com'],
            ['key' => 'contact_email_alt_2', 'label' => 'Alternate Email 2', 'value' => 'zamzamcanada23@gmail.com'],
        ];

        foreach ($generalSettings as $setting) {
            SystemSetting::create([
                'group' => 'general',
                'key' => $setting['key'],
                'label' => $setting['label'],
                'value' => $setting['value'],
                'is_active' => true,
            ]);
        }
    }
}
