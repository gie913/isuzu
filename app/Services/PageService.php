<?php

namespace App\Services;

use App\Repositories\Sql\SqlPageRepository;

class PageService extends BaseService
{
    protected SqlPageRepository $PageRepository;

    public function __construct(SqlPageRepository $PageRepository)
    {
        parent::__construct($PageRepository);
        $this->PageRepository = $PageRepository;
    }

    public function findModuleCode(): string
    {
        return 'page';
    }

    public function findModuleName(): string
    {
        return 'Pages';
    }
}