<?php

namespace App\Facades;

use App\Helpers\CMSAccessHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \stdClass findByMRoleIdByMCMSMenuCode(string|null $m_role_id, string $m_cms_menu_code)
 * @method static mixed findMenuNameById(string $m_page_menu_id)
 * @method static bool can(string $m_cms_menu_code, string $action = 'can_read')
 *
 * @see \App\Helpers\CMSAccessHelper
 */
class ModuleAccess extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cms.access';
    }
}
