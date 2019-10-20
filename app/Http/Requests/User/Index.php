<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\User;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\UserFilter;
use VOSTPT\Http\Requests\Request;
use VOSTPT\Models\User;
use VOSTPT\Rules\ValidRole;

class Index extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        return $this->user()->can('index', User::class);
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'page.number' => [
                'integer',
            ],
            'page.size' => [
                'integer',
            ],
            'ids.*' => [
                Rule::exists('users', 'id'),
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'sort' => [
                Rule::in(UserFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(UserFilter::getOrderValues()),
            ],
            'roles.*' => [
                new ValidRole(),
            ],
        ];
    }
}
