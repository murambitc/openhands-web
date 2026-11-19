<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $orgs = [
            ['name' => 'Friends of Diversity', 'description' => 'Supports LGBTI youth through peer support, counseling & coaching', 'phone' => '77649963', 'whatsapp' => '26777649963', 'email' => 'admin@friendsofdiversity.org.bw', 'latitude' => -24.650818, 'longitude' => 25.917332],
            ['name' => 'Ditshwanelo', 'description' => 'Botswana Centre for Human Rights focusing on justice & advocacy', 'phone' => '3906698', 'whatsapp' => '2673906698', 'email' => 'admin.ditshwanelo@info.bw', 'latitude' => -24.650818, 'longitude' => 25.917332],
            ['name' => 'LEGABIBO', 'description' => 'LGBTIQ+ rights organisation promoting equality & dignity', 'email' => 'legabibo@legabibo.org.bw', 'latitude' => -24.653, 'longitude' => 25.906],
            ['name' => 'SISONKE Botswana', 'description' => 'Advocacy and support organisation', 'phone' => '72553200', 'whatsapp' => '26772553200', 'email' => 'toshb@sisonkebotswana.org.bw', 'latitude' => -24.658, 'longitude' => 25.912],
            ['name' => 'Silence Kills Support Group', 'description' => 'Mental health awareness & support', 'phone' => '75944474', 'whatsapp' => '26775944474', 'email' => 'info@silencekills.online', 'latitude' => -24.66, 'longitude' => 25.9],
            ['name' => 'Valor Mental Health Foundation', 'description' => 'Mental health support services', 'phone' => '73060044', 'whatsapp' => '26773060044', 'email' => 'info@vmhf.co.bw', 'latitude' => -24.64, 'longitude' => 25.91],
            ['name' => 'PsycBotsogo', 'description' => 'Professional psychological services', 'phone' => '3111194', 'whatsapp' => '26776031085', 'email' => 'info@psycbotsogo.co.bw', 'latitude' => -24.67, 'longitude' => 25.92],
            ['name' => 'Peo Clinical Psychology', 'description' => 'Clinical psychology consultancy services', 'phone' => '73824717', 'whatsapp' => '26773824717', 'email' => 'info@peopsych.co.bw', 'latitude' => -24.665, 'longitude' => 25.918],
            ['name' => 'Serenity Wellness Center', 'description' => 'Wellness and mental health support', 'phone' => '74000000', 'whatsapp' => '26774000000', 'email' => 'info@serenitywellness.co.bw', 'latitude' => -24.68, 'longitude' => 25.93],
        ];

        foreach ($orgs as $org) {
            Organization::firstOrCreate(['name' => $org['name']], $org);
        }
    }
}