<?php

namespace App\Services;

use App\Repositories\Sql\SqlBaseSetupRepository;

class BaseSetupService extends BaseService
{
    protected SqlBaseSetupRepository $BaseSetupRepository;

    public function __construct(SqlBaseSetupRepository $BaseSetupRepository)
    {
        parent::__construct($BaseSetupRepository);
        $this->BaseSetupRepository = $BaseSetupRepository;
    }

    public function findModuleCode(): string
    {
        return 'base_setup';
    }

    public function findModuleName(): string
    {
        return 'Base Setups';
    }
}