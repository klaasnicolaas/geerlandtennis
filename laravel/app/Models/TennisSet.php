<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TennisSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'tennis_match_id',
        'set_number',
        'team_one_score',
        'team_two_score',
        'has_tie_break',
    ];

    /**
     * Relationships to other models.
     */
    public function tennisMatch(): BelongsTo
    {
        return $this->belongsTo(TennisMatch::class);
    }
}
