<?php

namespace App\Services;

use App\Repositories\Sql\SqlCMSMenuRepository;

class CMSMenuService extends BaseService
{
    protected SqlCMSMenuRepository $cmsMenuRepository;

    public function __construct(SqlCMSMenuRepository $cmsMenuRepository)
    {
        parent::__construct($cmsMenuRepository);
        $this->cmsMenuRepository = $cmsMenuRepository;
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
        $menu = $this->cmsMenuRepository->findByCode($code);

        if (!$menu) {
            return $this->formatResponse(false, null, 'Menu not found.');
        }

        return $this->formatResponse(true, $menu, 'Menu retrieved successfully.');
    }
}
