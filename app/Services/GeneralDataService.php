<?php

namespace App\Services;

use App\Repositories\Sql\SqlGeneralDataRepository;

class GeneralDataService extends BaseService
{
    protected SqlGeneralDataRepository $GeneralDataRepository;

    public function __construct(SqlGeneralDataRepository $GeneralDataRepository)
    {
        parent::__construct($GeneralDataRepository);
        $this->GeneralDataRepository = $GeneralDataRepository;
    }

    public function findModuleCode(): string
    {
        return 'general_data';
    }

    public function findModuleName(): string
    {
        return 'General Datas';
    }
}