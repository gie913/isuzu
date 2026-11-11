<?php

namespace App\Services;

use App\Repositories\Sql\SqlAttachmentRepository;

class AttachmentService extends BaseService
{
    protected SqlAttachmentRepository $AttachmentRepository;

    public function __construct(SqlAttachmentRepository $AttachmentRepository)
    {
        parent::__construct($AttachmentRepository);
        $this->AttachmentRepository = $AttachmentRepository;
    }

    public function findModuleCode(): string
    {
        return 'attachment';
    }

    public function findModuleName(): string
    {
        return 'Attachments';
    }
}