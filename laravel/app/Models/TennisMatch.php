<?php

namespace App\Models;

use App\Enums\MatchType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TennisMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_one_id',
        'team_two_id',
        'winner_team_id',
        'tournament_id',
        'match_date',
        'match_type',
        'is_practice',
    ];

    protected $casts = [
        'match_type' => MatchType::class,
    ];

    /**
     * Relationships to other models.
     */
    // Teams that participate in the match.
    public function teamOne(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_one_id');
    }

    public function teamTwo(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_two_id');
    }

    public function winnerTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    // Many sets that make up the match.
    public function sets(): HasMany
    {
        return $this->hasMany(TennisSet::class);
    }

    // The tournament the match is part of.
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }
}
