<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'mood', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMoodScoreAttribute(): int
    {
        return match (true) {
            str_contains(strtolower($this->mood), 'happy'),
            str_contains(strtolower($this->mood), 'joyful'),
            str_contains(strtolower($this->mood), 'excited') => 3,
            str_contains(strtolower($this->mood), 'content'),
            str_contains(strtolower($this->mood), 'relaxed') => 2,
            str_contains(strtolower($this->mood), 'sad'),
            str_contains(strtolower($this->mood), 'crying') => -2,
            str_contains(strtolower($this->mood), 'stressed'),
            str_contains(strtolower($this->mood), 'anxious') => -2,
            default => 0,
        };
    }
}