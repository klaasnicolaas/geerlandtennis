<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'rating_singles',
        'rating_doubles',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Get the user's avatar URL.
     *
     * @return string|null Returns the user's avatar URL.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    /**
     * Checks if a user can access a panel based on their email and email verification status.
     *
     * @param  Panel  $panel  The panel object that the user is trying to access.
     * @return bool Returns a boolean value indicating if the user can access the panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole([UserRole::ADMIN, UserRole::MODERATOR]);
        }
        if ($panel->getId() === 'app') {
            return true;
        }

        // @codeCoverageIgnoreStart
        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Relationship with other models.
     */
    // Teams that the user is part of.
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    // Get available teammates for a specific tournament
    public function getAvailableTeammates(int $tournamentId): array|Collection
    {
        return User::whereDoesntHave('teams', function ($query) use ($tournamentId): void {
            $query->whereHas('tournaments', function ($tournamentQuery) use ($tournamentId): void {
                $tournamentQuery->where('tournament_id', $tournamentId);
            });
        })->where('id', '<>', $this->id)->get();
    }

    // Check if a user is already registered in a team for a specific tournament
    public function isRegisteredForTournament(Tournament $tournament): bool
    {
        return $this->teams()
            ->whereHas('tournaments', function ($query) use ($tournament): void {
                $query->where('tournament_id', $tournament->id);
            })
            ->exists();
    }
}
