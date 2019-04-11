<?php

declare(strict_types=1);

namespace VOSTPT\Rules;

use Illuminate\Contracts\Validation\Rule;
use VOSTPT\Models\Role;

class ValidRole implements Rule
{
    /**
     * {@inheritDoc}
     */
    public function passes($attribute, $value): bool
    {
        return Role::where('name', $value)->first() !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function message(): string
    {
        return \sprintf('The :attribute value must be one of: %s', \implode(', ', Role::pluck('name')->all()));
    }
}
