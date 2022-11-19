<?php

namespace App\Policies;

use App\Models\Commands\RealEstate;
use App\Models\Commands\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RealEstatePolicy
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
     * @param  \App\Models\Commands\RealEstate  $realEstate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, RealEstate $realEstate)
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
     * @param  \App\Models\Commands\RealEstate  $realEstate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, RealEstate $realEstate)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\RealEstate  $realEstate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, RealEstate $realEstate)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\RealEstate  $realEstate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, RealEstate $realEstate)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\RealEstate  $realEstate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, RealEstate $realEstate)
    {
        //
    }
}
