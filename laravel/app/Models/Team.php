<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relationships to other models.
     */
    // Users that are part of the team.
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user');
    }

    // Tournaments that the team participates in.
    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class, 'team_tournament')
                    ->withPivot('registration_date', 'seed_number', 'status')
                    ->withTimestamps();
    }

    // Matches that the team participates in.
    public function matches(): HasMany
    {
        return $this->hasMany(TennisMatch::class);
    }
}
