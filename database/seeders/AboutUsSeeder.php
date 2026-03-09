<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'about_us_heading', 'value' => 'Our Story', 'group' => 'about_us', 'label' => 'About Us Heading'],
            ['key' => 'about_us_badge_text', 'value' => '15+', 'group' => 'about_us', 'label' => 'About Us Badge Text'],
            ['key' => 'about_us_badge_subtext', 'value' => 'YEARS OF EXCELLENCE', 'group' => 'about_us', 'label' => 'About Us Badge Subtext'],
            ['key' => 'about_us_description_1', 'value' => 'Established with a vision to bridge the gap between South Asian manufacturers and Canadian consumers, Zam Zam Import Export Inc. has grown into a cornerstone of the ethnic food distribution industry.', 'group' => 'about_us', 'label' => 'About Us Description 1'],
            ['key' => 'about_us_description_2', 'value' => 'We started as a small operation with a single truck and a passion for quality. Today, we operate a state-of-the-art distribution network that serves hundreds of retailers across the country.', 'group' => 'about_us', 'label' => 'About Us Description 2'],
            ['key' => 'about_us_description_3', 'value' => 'Our success is built on trust, reliability, and an unwavering commitment to quality. We partner directly with top brands to ensure that every product we deliver meets the highest standards.', 'group' => 'about_us', 'label' => 'About Us Description 3'],
            ['key' => 'about_us_feature_1_title', 'value' => 'Authenticity', 'group' => 'about_us', 'label' => 'About Us Feature 1 Title'],
            ['key' => 'about_us_feature_1_desc', 'value' => '100% genuine products sourced directly from manufacturers.', 'group' => 'about_us', 'label' => 'About Us Feature 1 Desc'],
            ['key' => 'about_us_feature_2_title', 'value' => 'Reliability', 'group' => 'about_us', 'label' => 'About Us Feature 2 Title'],
            ['key' => 'about_us_feature_2_desc', 'value' => 'Consistent supply chain and timely deliveries you can count on.', 'group' => 'about_us', 'label' => 'About Us Feature 2 Desc'],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
