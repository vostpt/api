<?php

declare(strict_types=1);

namespace VOSTPT\Policies;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        return $user->isAn(Role::ADMINISTRATOR);
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
        return $user->isAn(Role::ADMINISTRATOR);
    }

    /**
     * Determine whether the User can update other Users.
     *
     * @param User $user
     * @param User $userToUpdate
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return bool
     */
    public function update(User $user, User $userToUpdate): bool
    {
        if (! $user->isAn(Role::ADMINISTRATOR)) {
            return false;
        }

        if ($user->getKey() !== $userToUpdate->getKey()) {
            return true;
        }

        throw new AccessDeniedHttpException('User cannot self update');
    }
}
