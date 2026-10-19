<?php

namespace App\Repositories\Sql;

use App\Models\User;

class SqlUserRepository extends SqlBaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find active user by username.
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User
    {
        return $this->model->newQuery()
            ->where('username', $username)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Find active user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->newQuery()
            ->where('email', $email)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Find active user by username or email.
     *
     * @param string $identifier
     * @return User|null
     */
    public function findByUsernameOrEmail(string $identifier): ?User
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->where(function ($query) use ($identifier) {
                $query->where('username', $identifier)
                    ->orWhere('email', $identifier);
            })
            ->first();
    }
}
