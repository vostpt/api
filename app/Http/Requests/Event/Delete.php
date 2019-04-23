<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Event;

use VOSTPT\Http\Requests\Request;

class Delete extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        $event = $this->route('event');

        return $this->user()->can('delete', $event);
    }
}
