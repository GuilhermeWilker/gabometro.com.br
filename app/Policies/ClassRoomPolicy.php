<?php

namespace App\Policies;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassRoomPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassRoom $classRoom): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canManage();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassRoom $classRoom): bool
    {
        return $user->canManage();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassRoom $classRoom): bool
    {
        return $user->canManage();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClassRoom $classRoom): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClassRoom $classRoom): bool
    {
        return false;
    }
}
