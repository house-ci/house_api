<?php

namespace App\Policies;

use App\Models\Commands\PropertyType;
use App\Models\Commands\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyTypePolicy
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
     * @param  \App\Models\Commands\PropertyType  $propertyType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PropertyType $propertyType)
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
     * @param  \App\Models\Commands\PropertyType  $propertyType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PropertyType $propertyType)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\PropertyType  $propertyType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PropertyType $propertyType)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\PropertyType  $propertyType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PropertyType $propertyType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Commands\User  $user
     * @param  \App\Models\Commands\PropertyType  $propertyType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PropertyType $propertyType)
    {
        //
    }
}
