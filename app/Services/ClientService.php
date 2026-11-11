<?php

namespace App\Services;

use App\Repositories\Sql\SqlClientRepository;

class ClientService extends BaseService
{
    protected SqlClientRepository $ClientRepository;

    public function __construct(SqlClientRepository $ClientRepository)
    {
        parent::__construct($ClientRepository);
        $this->ClientRepository = $ClientRepository;
    }

    public function findModuleCode(): string
    {
        return 'client';
    }

    public function findModuleName(): string
    {
        return 'Clients';
    }
}