<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidPlanType extends Model
{
    use HasFactory;

    protected $table = 'paid_plan_type';

    protected $fillable = [
        'paid_type',
        'price'
    ];
}
