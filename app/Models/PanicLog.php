<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanicLog extends Model
{
    protected $fillable = ['user_id', 'latitude', 'longitude', 'resolved'];

    protected $casts = [
        'resolved' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}