<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\User;

use VOSTPT\Http\Requests\Request;
use VOSTPT\Models\User;

class View extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        return $this->user()->can('view', User::class);
    }
}
