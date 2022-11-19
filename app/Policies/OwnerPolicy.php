<?php

namespace App\Policies;

use App\Models\Commands\Owner;
use App\Models\Commands\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OwnerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Commands\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Owner $owner)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Commands\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Owner $owner)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Owner $owner)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Owner $owner)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Owner $owner)
    {
        //
    }
}
