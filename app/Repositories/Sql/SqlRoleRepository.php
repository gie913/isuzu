<?php

namespace App\Repositories\Sql;

use App\Models\Role;

class SqlRoleRepository extends SqlBaseRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}