<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingData extends Model
{
    use HasFactory;

    protected $table = 'matching_data';

    protected $fillable = [
        'proposed_user_id',
        'proposed_user_nickname',
        'proposed_date',
        'accepted_user_id',
        'accepted_user_nickname',
        'accepted_date',
        'receiving_message_state',
        'proposal_state'
    ];
}
