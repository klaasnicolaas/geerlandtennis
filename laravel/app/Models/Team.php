<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // Tournaments that the team participates in.
    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class, 'team_tournament')->withTimestamps();
    }
}
