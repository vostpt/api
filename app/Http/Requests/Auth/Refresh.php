<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Auth;

use VOSTPT\Http\Requests\Request;
use VOSTPT\Models\User;

class Refresh extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        return $this->user()->can('refresh', User::class);
    }
}
