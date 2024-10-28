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
        return $this->belongsToMany(Team::class, 'team_tournament')
            ->withPivot('registration_date', 'seed_number', 'status')
            ->withTimestamps();
    }

    // Matches that are part of the tournament.
    public function tennisMatches(): HasMany
    {
        return $this->hasMany(TennisMatch::class);
    }

    /**
     * Helper methods.
     */
    // Create a team with a single user.
    public function createSingleTeam($user): Team
    {
        $team = Team::create([
            'name' => "{$user->name} (Singles)",
        ]);
        $team->users()->attach($user->id);

        return $team;
    }

    // Create a team with two users.
    public function createDoubleTeam($user, $teammate): Team
    {
        $team = Team::create([
            'name' => "{$user->name} & {$teammate->name}",
        ]);
        $team->users()->attach([$user->id, $teammate->id]);

        return $team;
    }
}
