<?php

namespace App\Services;

use App\Repositories\Sql\SqlModuleRepository;

class ModuleService extends BaseService
{
    protected SqlModuleRepository $moduleRepository;

    public function __construct(SqlModuleRepository $moduleRepository)
    {
        parent::__construct($moduleRepository);
        $this->moduleRepository = $moduleRepository;
    }

    public function findModuleCode(): string
    {
        return 'cms_menu';
    }

    public function findModuleName(): string
    {
        return 'CMS Menu';
    }

    /**
     * Find menu by code/name.
     *
     * @param string $code
     * @return array
     */
    public function findByCode(string $code): array
    {
        $menu = $this->moduleRepository->findByCode($code);

        if (!$menu) {
            return $this->formatResponse(false, null, 'Menu not found.');
        }

        return $this->formatResponse(true, $menu, 'Menu retrieved successfully.');
    }

    /**
     * Get module tree hierarchy.
     *
     * @return array
     */
    public function getTree(): array
    {
        $tree = $this->moduleRepository->getTree();
        return $this->formatResponse(true, $tree, 'Module tree retrieved successfully.');
    }
}
