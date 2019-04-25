<?php

declare(strict_types=1);

namespace VOSTPT\Policies;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use VOSTPT\Models\Event;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;

class EventPolicy
{
    /**
     * Determine whether the User can create Events.
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
     * Determine whether the User can update Events.
     *
     * @param User  $user
     * @param Event $event
     *
     * @return bool
     */
    public function update(User $user, Event $event): bool
    {
        return $user->isAn(Role::ADMINISTRATOR);
    }

    /**
     * Determine whether the User can delete Events.
     *
     * @param User  $user
     * @param Event $event
     *
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return bool
     */
    public function delete(User $user, Event $event): bool
    {
        if (! $user->isAn(Role::ADMINISTRATOR)) {
            return false;
        }

        if ($event->occurrences()->count() > 0) {
            throw new AccessDeniedHttpException('Events with Occurrences cannot be deleted');
        }

        return true;
    }
}
