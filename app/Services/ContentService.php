<?php

namespace App\Services;

use App\Repositories\Sql\SqlContentRepository;

class ContentService extends BaseService
{
    protected SqlContentRepository $ContentRepository;

    public function __construct(SqlContentRepository $ContentRepository)
    {
        parent::__construct($ContentRepository);
        $this->ContentRepository = $ContentRepository;
    }

    public function findModuleCode(): string
    {
        return 'content';
    }

    public function findModuleName(): string
    {
        return 'Contents';
    }
}