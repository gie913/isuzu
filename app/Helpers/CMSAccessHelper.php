<?php

namespace App\Helpers;

use App\Services\CMSAccessService;
use App\Services\CMSMenuService;
use Illuminate\Support\Facades\Auth;
use stdClass;

class CMSAccessHelper
{
    private $cms_access_service;
    private $cms_menu_service;

    public function __construct(
        CMSAccessService $cms_access_service,
        CMSMenuService $cms_menu_service
    ) {
        $this->cms_access_service = $cms_access_service;
        $this->cms_menu_service = $cms_menu_service;
    }

    public function findByMRoleIdByMCMSMenuCode($m_role_id, $m_cms_menu_code)
    {
        $page_access = $this->cms_access_service->findByMRoleIdByMCMSMenuCode($m_role_id, $m_cms_menu_code);
        if ($page_access['success'] == true) {
            $page_access_obj = $page_access['data'];
            if ($page_access_obj->is_submenu == 1) {
                // get parent of sub menu
                $parent_menu = $this->cms_menu_service->find($page_access_obj->submenu_from_id)['data'];
                $level_1_menu = $parent_menu?->name ?? $m_cms_menu_code;
            } else {
                $parent_menu = null;
                $level_1_menu = $m_cms_menu_code;
            }

            $obj = new stdClass();
            $obj->can_create = ($page_access_obj->can_create == 1) ? true : false;
            $obj->can_read = ($page_access_obj->can_read == 1) ? true : false;
            $obj->can_update = ($page_access_obj->can_update == 1) ? true : false;
            $obj->can_delete = ($page_access_obj->can_delete == 1) ? true : false;
            $obj->level_1_menu = $level_1_menu;
        } else {
            $obj = new stdClass();
            $obj->can_create = false;
            $obj->can_read = false;
            $obj->can_update = false;
            $obj->can_delete = false;
            $obj->level_1_menu = null;
        }

        return $obj;
    }

    public function findMenuNameById($m_page_menu_id)
    {
        return $this->cms_menu_service->find($m_page_menu_id);
    }

    /**
     * Helper to verify if the currently authenticated user can perform the given action on a module.
     *
     * @param string $m_cms_menu_code
     * @param string $action ('can_read', 'can_create', 'can_update', 'can_delete')
     * @return bool
     */
    public function can(string $m_cms_menu_code, string $action = 'can_read'): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        $roleId = $user->m_role_id ?? 'super-admin';

        $access = $this->findByMRoleIdByMCMSMenuCode($roleId, $m_cms_menu_code);

        return !empty($access->$action);
    }
}
