<?php

namespace App\Rules;

use App\Models\TennisSet;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueSetNumber implements ValidationRule
{
    protected $tennisMatchId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $tennisMatchId
     */
    public function __construct($tennisMatchId)
    {
        $this->tennisMatchId = $tennisMatchId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the set number already exists for this tennis match
        $exists = TennisSet::where('tennis_match_id', $this->tennisMatchId)
            ->where('set_number', $value)
            ->exists();

        if ($exists) {
            $fail("This set number already exists for the match.");
        }
    }
}
