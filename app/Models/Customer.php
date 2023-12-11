<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';

    protected $fillable = [
        'id',
        'user_name',
        'email',
        'user_nickname',
        'address',
        'community',
        'height',
        'body_type',
        'use_purpose',
        'intro_badge',
        'photo',
        'introduce',
        'plan_type',
        'likes_rate',
        'coin',
        'idetity_state'
    ];
}
