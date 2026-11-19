<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestCheckin extends Model
{
    protected $fillable = ['guest_name', 'mood', 'comment'];
}