<?php

declare(strict_types=1);

namespace VOSTPT\Policies;

use VOSTPT\Models\Occurrence;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;

class OccurrencePolicy
{
    /**
     * Determine whether the User can update Acronyms.
     *
     * @param User       $user
     * @param Occurrence $occurrence
     *
     * @return bool
     */
    public function update(User $user, Occurrence $occurrence): bool
    {
        return $user->isAn(Role::ADMINISTRATOR, Role::MODERATOR);
    }
}
