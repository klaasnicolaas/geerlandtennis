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
     * All teams that are part of the tournament.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_tournament')
            ->withPivot('registration_date', 'seed_number', 'status')
            ->withTimestamps();
    }

    /**
     * Matches that are part of the tournament.
     */
    public function tennisMatches(): HasMany
    {
        return $this->hasMany(TennisMatch::class);
    }

    /**
     * Register a user for the tournament with an optional teammate.
     */
    public function registerTeamTournament(User $user, ?int $teammateId = null): void
    {
        // Find or create the team for the user(s).
        if ($this->tournament_type === MatchType::SINGLE) {
            $team = $this->findOrCreateTeam(
                [$user],
                "{$user->name} (Singles)"
            );
        } else {
            $teammate = User::findOrFail($teammateId);
            $team = $this->findOrCreateTeam(
                [$user, $teammate],
                "{$user->name} & {$teammate->name}"
            );
        }

        // Register the team for the tournament.
        $this->teams()->attach($team->id, [
            'registration_date' => now(),
            'status' => 'registered',
        ]);
    }

    /**
     * Find or create a team with the given users.
     */
    public function findOrCreateTeam(array $users, string $teamName): Team
    {
        // Generate a team hash from the user IDs.
        $userIds = array_map(fn ($user) => $user instanceof User ? $user->id : $user, $users);
        $teamHash = Team::generateTeamHash($userIds);

        // Check if a team with the same hash already exists.
        $existingTeam = Team::where('team_hash', $teamHash)->first();
        if ($existingTeam) {
            return $existingTeam;
        }

        // Create a new team
        $team = Team::create([
            'name' => $teamName,
            'team_hash' => $teamHash,
        ]);
        $team->users()->attach($userIds);

        return $team;
    }
}
