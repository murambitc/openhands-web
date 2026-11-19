<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'footer_text' => '© 2026 Open Hands App. All rights reserved.',
            'site_name' => 'Open Hands',
            'contact_email' => 'support@openhands.co.bw',
            'emergency_phone' => '999',
            'ambulance_phone' => '997',
            'whatsapp_emergency' => '26777649963',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['setting_key' => $key], ['setting_value' => $value]);
        }
    }
}