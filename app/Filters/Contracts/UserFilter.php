<?php

declare(strict_types=1);

namespace VOSTPT\Filters\Contracts;

interface UserFilter extends Filter
{
    /**
     * Include Roles for filtering.
     *
     * @param array $roles
     *
     * @throws \OutOfBoundsException
     *
     * @return self
     */
    public function withRoles(array $roles): self;
}
