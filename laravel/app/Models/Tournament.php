<?php

namespace App\Models;

use App\Enums\MatchType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'tournament_type',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'tournament_type' => MatchType::class,
    ];

    /**
     * Relationships to other models.
     */
    // Teams that participate in the tournament.
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_tournament')->withTimestamps();
    }

    // Matches that are part of the tournament.
    public function tennisMatches(): HasMany
    {
        return $this->hasMany(TennisMatch::class);
    }
}
