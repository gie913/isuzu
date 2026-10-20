<?php

namespace App\Repositories\Sql;

use App\Models\Module;
use App\Models\ModuleAccess;

class SqlModuleAccessRepository extends SqlBaseRepository
{
    public function __construct(ModuleAccess $model)
    {
        parent::__construct($model);
    }

    /**
     * Find access by role ID and menu code/name.
     *
     * @param string $m_role_id
     * @param string $m_cms_menu_code
     * @return object|null
     */
    public function findByMRoleIdByMCMSMenuCode(string $m_role_id, string $m_cms_menu_code): ?object
    {
        $menu = Module::where('is_active', true)
            ->where(function ($q) use ($m_cms_menu_code) {
                $q->where('name', $m_cms_menu_code)
                    ->orWhere('id', $m_cms_menu_code);
            })
            ->first();

        if (!$menu) {
            // For super-admin role, allow full access by default
            if (strtolower($m_role_id) === 'super-admin') {
                return (object) [
                    'is_submenu'        => 0,
                    'submenu_from_id'   => null,
                    'can_read'          => 1,
                    'can_create'        => 1,
                    'can_update'        => 1,
                    'can_delete'        => 1,
                ];
            }
            return null;
        }

        $access = $this->model->newQuery()
            ->where('m_role_id', $m_role_id)
            ->where('m_module_id', $menu->id)
            ->where('is_active', true)
            ->first();

        $canRead = $access ? (int) $access->can_read : 0;
        $canCreate = $access ? (int) $access->can_create : 0;
        $canUpdate = $access ? (int) $access->can_update : 0;
        $canDelete = $access ? (int) $access->can_delete : 0;

        // If user is super-admin and no record exists, allow full access by default
        if (!$access && strtolower($m_role_id) === 'super-admin') {
            $canRead = 1;
            $canCreate = 1;
            $canUpdate = 1;
            $canDelete = 1;
        }

        return (object) [
            'is_submenu'        => !empty($menu->parent_id) ? 1 : 0,
            'submenu_from_id'   => $menu->parent_id,
            'can_read'          => $canRead,
            'can_create'        => $canCreate,
            'can_update'        => $canUpdate,
            'can_delete'        => $canDelete,
            'm_cms_menu_code'   => $menu->name,
            'm_cms_menu_label'  => $menu->label,
            'm_module_id'       => $menu->id,
            'm_role_id'         => $m_role_id,
        ];
    }
}
