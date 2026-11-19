<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'age_group', 'gender', 'pronouns', 'orientation',
        'counselor_preference', 'therapist_history', 'main_concern',
        'mental_rating', 'medication', 'support_system', 'substance_use',
        'sleep', 'self_harm', 'danger', 'support_need', 'risk_level',
        'preferred_referral',
    ];

    protected $casts = [
        'risk_level' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'preferred_referral');
    }

    protected static function booted(): void
    {
        static::created(function (Screening $screening) {
            // Auto-update user's risk_level
            $screening->user()->update(['risk_level' => $screening->risk_level]);
        });
    }
}