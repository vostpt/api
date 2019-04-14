<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Illuminate\Database\Eloquent\Builder;
use OutOfBoundsException;
use VOSTPT\Models\Role;

class UserFilter extends Filter implements Contracts\UserFilter
{
    /**
     * Roles for filtering.
     *
     * @var array
     */
    private $roles = [];

    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'users';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'users.id',
            'users.email',
            'users.name',
            'users.surname',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getSortableColumns(): array
    {
        return [
            'email',
            'name',
            'surname',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getColumns(): array
    {
        return [
            'users.id',
            'users.email',
            'users.name',
            'users.surname',
            'users.created_at',
            'users.updated_at',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function withRoles(array $roles): Contracts\UserFilter
    {
        $availableRoles = Role::pluck('name')->all();

        // If there's a difference, the values are invalid
        if (\array_diff($roles, $availableRoles)) {
            throw new OutOfBoundsException(\sprintf(
                'The role values must be one of: %s',
                \implode(', ', $availableRoles)
            ));
        }

        $this->roles = \array_unique($roles, SORT_STRING);

        \sort($this->roles, SORT_STRING);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): void
    {
        parent::apply($builder);

        // Apply User Role filtering
        if ($this->roles) {
            $builder->whereIs(... $this->roles);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->roles),
        ]);
    }
}
