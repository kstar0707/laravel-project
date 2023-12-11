<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommCustomers extends Model
{
    use HasFactory;

    protected $table = 'today_recomm';

    protected $fillable = [
        'user_id',
        'nick_name',
        'recomm_user_id',
        'recomm_nickname'
    ];
}
