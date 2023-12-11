<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikesList extends Model
{
    use HasFactory;

    protected $table = 'likes_list';

    protected $fillable = [
        'sent_user_id',
        'received_user_id',
        'amount'
    ];
}
