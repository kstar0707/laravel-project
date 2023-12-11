<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $table = 'community';

    protected $fillable = [
        'community_name',
        'community_category',
        'community_photo'
    ];
}
