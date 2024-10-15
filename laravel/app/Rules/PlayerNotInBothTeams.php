<?php

namespace App\Rules;

use App\Models\Team;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PlayerNotInBothTeams implements ValidationRule
{
    protected $teamOneId;

    protected $teamTwoId;

    /**
     * Create a new rule instance.
     *
     * @param  int|null  $teamOneId
     * @param  int|null  $teamTwoId
     */
    public function __construct($teamOneId, $teamTwoId)
    {
        $this->teamOneId = $teamOneId;
        $this->teamTwoId = $teamTwoId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->teamOneId && $this->teamTwoId) {
            $teamOnePlayers = Team::find($this->teamOneId)
                ->users()
                ->pluck('users.id')
                ->toArray();

            $teamTwoPlayers = Team::find($this->teamTwoId)
                ->users()
                ->pluck('users.id')
                ->toArray();

            // Find the common player IDs in both teams
            $commonPlayerIds = array_intersect($teamOnePlayers, $teamTwoPlayers);

            if (! empty($commonPlayerIds)) {
                // Find the names of the common players
                $commonPlayerNames = \App\Models\User::whereIn('id', $commonPlayerIds)
                    ->pluck('name')
                    ->toArray();

                // Create a string of the common player names to include in the error message
                $playerNamesString = implode(separator: ', ', array: $commonPlayerNames);
                $fail("De volgende speler(s) zitten in beide teams: $playerNamesString.");
            }
        }
    }
}
