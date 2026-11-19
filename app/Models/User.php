<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;   // ⬅ add this

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;   // ⬅ add HasApiTokens here

    protected $fillable = [
    'name',
    'username',
    'email',
    'password',
    'role',
    'mood',
    'safety',
    'country',
    'location',
    'profile_image',
    'risk_level',
    'lat',
    'lng',
    'last_lat',
    'last_lon',
    'last_active',
];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

public function panicLogs()
{
    return $this->hasMany(PanicLog::class);
}

    public function latestScreening()
    {
        return $this->hasOne(Screening::class)->latestOfMany();
    }

    public function getProfileImageUrlAttribute(): string
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->username) . '&background=2F5DAA&color=fff&size=200';
    }
}