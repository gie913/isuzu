<?php

namespace App\Services;

use App\Repositories\Sql\SqlMessageRepository;

class MessageService extends BaseService
{
    protected SqlMessageRepository $MessageRepository;

    public function __construct(SqlMessageRepository $MessageRepository)
    {
        parent::__construct($MessageRepository);
        $this->MessageRepository = $MessageRepository;
    }

    public function findModuleCode(): string
    {
        return 'message';
    }

    public function findModuleName(): string
    {
        return 'Messages';
    }
}