<?php

declare(strict_types=1);

namespace VOSTPT\Models;

class Role extends \Silber\Bouncer\Database\Role
{
    public const ADMINISTRATOR = 'administrator';
    public const MODERATOR     = 'moderator';
    public const CONTRIBUTOR   = 'contributor';
}
