<?php

namespace App\Services;

use App\Repositories\Sql\SqlRoleRepository;

class RoleService extends BaseService
{
    protected SqlRoleRepository $RoleRepository;

    public function __construct(SqlRoleRepository $RoleRepository)
    {
        parent::__construct($RoleRepository);
        $this->RoleRepository = $RoleRepository;
    }

    public function findModuleCode(): string
    {
        return 'role';
    }

    public function findModuleName(): string
    {
        return 'Roles';
    }
}