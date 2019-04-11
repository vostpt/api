<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use DomainException;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use LengthException;
use Silber\Bouncer\Database\Concerns\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements Authenticatable, AuthorizableContract, JWTSubject
{
    use Authorizable;
    use HasRoles;

    /**
     * {@inheritDoc}
     */
    protected $table = 'users';

    /**
     * {@inheritDoc}
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthIdentifier()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthPassword()
    {
        return $this->attributes['password'];
    }

    /**
     * {@inheritDoc}
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function setRememberToken($value): void
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getRememberTokenName()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * {@inheritDoc}
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'surname' => $this->surname,
            'email'   => $this->email,
            'roles'   => $this->roles()->pluck('name')->all(),
        ];
    }

    /**
     * Set the name.
     *
     * @param string $name
     *
     * @throws \LengthException
     *
     * @return void
     */
    public function setNameAttribute(string $name): void
    {
        if ($name && \mb_strlen($name) > 255) {
            throw new LengthException('The name cannot have more than 255 characters');
        }

        $this->attributes['name'] = $name;
    }

    /**
     * Set the surname.
     *
     * @param string $surname
     *
     * @throws \LengthException
     *
     * @return void
     */
    public function setSurnameAttribute(string $surname = null): void
    {
        if ($surname && \mb_strlen($surname) > 255) {
            throw new LengthException('The surname cannot have more than 255 characters');
        }

        $this->attributes['surname'] = $surname;
    }

    /**
     * Set the Email address.
     *
     * @param string $email
     *
     * @throws \DomainException
     *
     * @return void
     */
    public function setEmailAttribute(string $email): void
    {
        if (! \filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException(\sprintf('"%s" is not a valid email address', $email));
        }

        $this->attributes['email'] = $email;
    }

    /**
     * Set the password.
     *
     * @param string $password
     *
     * @throws \DomainException
     *
     * @return void
     */
    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
