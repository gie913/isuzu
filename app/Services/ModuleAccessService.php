<?php

namespace App\Services;

use App\Repositories\Sql\SqlModuleAccessRepository;

class ModuleAccessService extends BaseService
{
    protected SqlModuleAccessRepository $moduleAccessRepository;

    public function __construct(SqlModuleAccessRepository $moduleAccessRepository)
    {
        parent::__construct($moduleAccessRepository);
        $this->moduleAccessRepository = $moduleAccessRepository;
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
     * Find access by Role ID and CMS Module Code.
     *
     * @param string|null $m_role_id
     * @param string $m_cms_module_code
     * @return array
     */
    public function findByMRoleIdByMCMSModuleCode(?string $m_role_id, string $m_cms_module_code): array
    {
        if (empty($m_role_id)) {
            return $this->formatResponse(false, null, 'Role ID is required.');
        }

        $access = $this->moduleAccessRepository->findByMRoleIdByMCMSModuleCode($m_role_id, $m_cms_module_code);

        if (!$access) {
            return $this->formatResponse(false, null, 'Access not found.');
        }

        return $this->formatResponse(true, $access, 'Access retrieved successfully.');
    }

    /**
     * Backward-compatible alias for findByMRoleIdByMCMSModuleCode.
     */
    public function findByMRoleIdByMCMSMenuCode(?string $m_role_id, string $m_cms_menu_code): array
    {
        return $this->findByMRoleIdByMCMSModuleCode($m_role_id, $m_cms_menu_code);
    }
}
