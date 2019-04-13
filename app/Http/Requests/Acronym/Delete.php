<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Acronym;

use VOSTPT\Http\Requests\Request;

class Delete extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        $acronym = $this->route('acronym');

        return $this->user()->can('delete', $acronym);
    }
}
