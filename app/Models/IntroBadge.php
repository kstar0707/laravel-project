<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntroBadge extends Model
{
    use HasFactory;

    protected $table = 'intro_badge';

    protected $fillable = [
        'tag_text',
        'tag_color'
    ];
}
