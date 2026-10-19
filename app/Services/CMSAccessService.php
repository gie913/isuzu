<?php

namespace App\Services;

use App\Repositories\Sql\SqlCMSAccessRepository;

class CMSAccessService extends BaseService
{
    protected SqlCMSAccessRepository $cmsAccessRepository;

    public function __construct(SqlCMSAccessRepository $cmsAccessRepository)
    {
        parent::__construct($cmsAccessRepository);
        $this->cmsAccessRepository = $cmsAccessRepository;
    }

    public function findModuleCode(): string
    {
        return 'cms_access';
    }

    public function findModuleName(): string
    {
        return 'CMS Access';
    }

    /**
     * Find access by Role ID and CMS Menu Code.
     *
     * @param string|null $m_role_id
     * @param string $m_cms_menu_code
     * @return array
     */
    public function findByMRoleIdByMCMSMenuCode(?string $m_role_id, string $m_cms_menu_code): array
    {
        if (empty($m_role_id)) {
            return $this->formatResponse(false, null, 'Role ID is required.');
        }

        $access = $this->cmsAccessRepository->findByMRoleIdByMCMSMenuCode($m_role_id, $m_cms_menu_code);

        if (!$access) {
            return $this->formatResponse(false, null, 'Access not found.');
        }

        return $this->formatResponse(true, $access, 'Access retrieved successfully.');
    }
}
