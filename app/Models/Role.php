<?php

declare(strict_types=1);

namespace VOSTPT\Models;

class Role extends \Silber\Bouncer\Database\Role
{
    public const ADMIN  = 'admin';
    public const WRITER = 'writer';
    public const READER = 'reader';
}
