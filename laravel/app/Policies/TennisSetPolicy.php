<?php

namespace App\Policies;

use App\Models\TennisSet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TennisSetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_tennis::set');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TennisSet $tennisSet): bool
    {
        return $user->can('view_tennis::set');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_tennis::set');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TennisSet $tennisSet): bool
    {
        return $user->can('update_tennis::set');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TennisSet $tennisSet): bool
    {
        return $user->can('delete_tennis::set');
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, TennisSet $tennisSet): bool
    // {
    //     return $user->can('restore_tennis::set');
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, TennisSet $tennisSet): bool
    // {
    //     return $user->can('force_delete_tennis::set');
    // }
}
