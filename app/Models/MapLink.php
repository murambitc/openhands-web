<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MapLink extends Model
{
    protected $fillable = ['map_url', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    /**
     * Return the currently active map URL, or null.
     * Defensive: never throws if the table is missing.
     */
    public static function active(): ?string
    {
        try {
            if (! Schema::hasTable('map_links')) {
                return null;
            }

            return static::where('is_active', true)
                ->latest()
                ->value('map_url');
        } catch (\Throwable $e) {
            \Log::warning('MapLink::active() failed: ' . $e->getMessage());
            return null;
        }
    }
}