<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;
use VOSTPT\Models\User;

class UserSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'users';

    /**
     * {@inheritDoc}
     */
    public function getLinks($model): array
    {
        return [
            'self' => route('users::view', [
                'User' => $model->getKey(),
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'email'      => $model->email,
            'name'       => $model->name,
            'surname'    => $model->surname,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Associated Roles.
     *
     * @param User $user
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function roles(User $user): Relationship
    {
        return new Relationship(new Collection($user->roles()->get(), new RoleSerializer()));
    }
}
