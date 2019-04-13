<?php

declare(strict_types=1);

namespace VOSTPT\Policies;

use VOSTPT\Models\Acronym;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;

class AcronymPolicy
{
    /**
     * Determine whether the User can create Acronyms.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isAn(Role::ADMINISTRATOR);
    }

    /**
     * Determine whether the User can update Acronyms.
     *
     * @param User    $user
     * @param Acronym $acronym
     *
     * @return bool
     */
    public function update(User $user, Acronym $acronym): bool
    {
        return $user->isAn(Role::ADMINISTRATOR);
    }

    /**
     * Determine whether the User can delete Acronyms.
     *
     * @param User    $user
     * @param Acronym $acronym
     *
     * @return bool
     */
    public function delete(User $user, Acronym $acronym): bool
    {
        return $user->isAn(Role::ADMINISTRATOR);
    }
}
