<?php

namespace App\Services;

use App\Repositories\Sql\SqlMenuRepository;

class MenuService extends BaseService
{
    protected SqlMenuRepository $MenuRepository;

    public function __construct(SqlMenuRepository $MenuRepository)
    {
        parent::__construct($MenuRepository);
        $this->MenuRepository = $MenuRepository;
    }

    public function findModuleCode(): string
    {
        return 'menu';
    }

    public function findModuleName(): string
    {
        return 'Menus';
    }
}