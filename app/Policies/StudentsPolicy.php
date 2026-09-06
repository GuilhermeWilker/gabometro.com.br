<?php

namespace App\Policies;

use App\Models\Students;
use App\Models\User;

class StudentsPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // todos da tenant veem a lista
    }

    public function view(User $user, Students $students): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, Students $students): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, Students $students): bool
    {
        return $user->canManage();
    }

    public function deleteAny(User $user): bool
    {
        return $user->canManage();
    }
}
