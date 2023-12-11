<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationReport extends Model
{
    use HasFactory;

    protected $table = 'violation_report';

    protected $fillable = [
        'violation_id',
        'user_id',
        'user_nickname',
        'violation_date',
        'violation_content',
    ];
}
