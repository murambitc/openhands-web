<?php

namespace App\Http\Controllers\Api;

use App\Models\MapLink;
use App\Models\Setting;

class SettingController extends ApiController
{
    /**
     * GET /api/v1/settings
     * Public — returns app-level config (footer, emergency numbers, map).
     */
    public function index()
    {
        return $this->ok([
            'site_name'          => Setting::get('site_name', 'Open Hands'),
            'footer_text'        => Setting::get('footer_text', ''),
            'emergency_phone'    => Setting::get('emergency_phone', '999'),
            'ambulance_phone'    => Setting::get('ambulance_phone', '997'),
            'emergency_whatsapp' => Setting::get('whatsapp_emergency', '26777649963'),
            'map_url'            => MapLink::active(),
        ]);
    }
}