<?php

namespace App\Services;

use App\Repositories\Sql\SqlContentDtlRepository;

class ContentDtlService extends BaseService
{
    protected SqlContentDtlRepository $ContentDtlRepository;

    public function __construct(SqlContentDtlRepository $ContentDtlRepository)
    {
        parent::__construct($ContentDtlRepository);
        $this->ContentDtlRepository = $ContentDtlRepository;
    }

    public function findModuleCode(): string
    {
        return 'content_dtl';
    }

    public function findModuleName(): string
    {
        return 'Content_dtls';
    }
}