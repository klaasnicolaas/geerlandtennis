<?php

namespace App\Rules;

use App\Models\Team;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SinglePlayerTeam implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Find the team and check if it has exactly one player
        $team = Team::find($value);
        if (! $team || $team->users()->count() !== 1) {
            $fail('This team must consist of a single player for a single match.');
        }
    }
}
