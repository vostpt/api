<?php

declare(strict_types=1);

namespace VOSTPT\Policies;

use VOSTPT\Models\Role;
use VOSTPT\Models\User;

class UserPolicy
{
    /**
     * Determine whether the User can index Users.
     *
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user): bool
    {
        return $user->isAn(Role::ADMIN);
    }

    /**
     * Determine whether the User can view another User.
     *
     * @param User $user
     *
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->isAn(Role::ADMIN);
    }
}
