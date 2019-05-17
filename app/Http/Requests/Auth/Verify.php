<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Auth;

use VOSTPT\Http\Requests\Request;
use VOSTPT\Models\User;

class Verify extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        return $this->user()->can('verify', User::class);
    }
}
