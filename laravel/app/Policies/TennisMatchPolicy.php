<?php

namespace App\Policies;

use App\Models\TennisMatch;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TennisMatchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_tennis::match');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TennisMatch $tennisMatch): bool
    {
        return $user->can('view_tennis::match');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_tennis::match');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TennisMatch $tennisMatch): bool
    {
        return $user->can('update_tennis::match');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TennisMatch $tennisMatch): bool
    {
        return $user->can('delete_tennis::match');
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, TennisMatch $tennisMatch): bool
    // {
    //     return $user->can('{{ restore }}');
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, TennisMatch $tennisMatch): bool
    // {
    //     return $user->can('force_delete_tennis_match');
    // }
}
