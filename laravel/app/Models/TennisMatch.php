<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TennisMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_type',
        'match_category',
        'team_one_player_one_id',
        'team_one_player_two_id',
        'team_two_player_one_id',
        'team_two_player_two_id',
        'match_date',
    ];

    public function sets(): HasMany
    {
        return $this->hasMany(Set::class, 'tennis_match_id');
    }

    public function teamOnePlayerOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_one_player_one_id');
    }

    public function teamOnePlayerTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_one_player_two_id');
    }

    public function teamTwoPlayerOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_two_player_one_id');
    }

    public function teamTwoPlayerTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_two_player_two_id');
    }
}
