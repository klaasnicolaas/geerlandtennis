<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Set extends Model
{
    use HasFactory;

    protected $fillable = [
        'tennis_match_id',
        'set_number',
        'team_one_score',
        'team_two_score',
        'tie_break',
        'winning_team',
    ];

    public function tennisMatch(): BelongsTo
    {
        return $this->belongsTo(TennisMatch::class);
    }
}
