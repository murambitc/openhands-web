<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WellnessSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'session_date', 'session_time',
        'location', 'image', 'is_active',
    ];

    protected $casts = [
        'session_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}