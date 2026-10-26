<?php

namespace App\Services;

use App\Models\ModuleAccess;
use App\Repositories\Sql\SqlRoleRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleService extends BaseService
{
    protected SqlRoleRepository $RoleRepository;

    public function __construct(SqlRoleRepository $RoleRepository)
    {
        parent::__construct($RoleRepository);
        $this->RoleRepository = $RoleRepository;
    }

    public function findModuleCode(): string
    {
        return 'role';
    }

    public function findModuleName(): string
    {
        return 'roles';
    }

    /**
     * Create role and sync module access permissions with hard delete & insert.
     *
     * @param array $roleData
     * @param array $moduleAccess
     * @return array
     */
    public function createWithAccess(array $roleData, array $moduleAccess = []): array
    {
        return DB::transaction(function () use ($roleData, $moduleAccess) {
            $role = $this->repository->create($roleData);

            $this->syncModuleAccess($role->id, $moduleAccess);

            return $this->formatResponse(true, $role, 'Role and permissions created successfully.');
        });
    }

    /**
     * Update role and sync module access permissions with hard delete & insert.
     *
     * @param string|int $id
     * @param array $roleData
     * @param array $moduleAccess
     * @return array
     */
    public function updateWithAccess(string|int $id, array $roleData, array $moduleAccess = []): array
    {
        return DB::transaction(function () use ($id, $roleData, $moduleAccess) {
            $role = $this->repository->update($id, $roleData);

            if (!$role) {
                return $this->formatResponse(false, null, 'Role not found or update failed.');
            }

            $this->syncModuleAccess($role->id, $moduleAccess);

            return $this->formatResponse(true, $role, 'Role and permissions updated successfully.');
        });
    }

    /**
     * Hard delete existing module access by m_role_id and re-insert given permissions.
     *
     * @param string $roleId
     * @param array $moduleAccess
     * @return void
     */
    public function syncModuleAccess(string $roleId, array $moduleAccess): void
    {
        // Hard delete all existing records for this role
        ModuleAccess::where('m_role_id', $roleId)->delete();

        $userId = Auth::check() ? (string) Auth::id() : 'system';

        foreach ($moduleAccess as $moduleId => $perms) {
            $canRead   = !empty($perms['can_read']) ? 1 : 0;
            $canCreate = !empty($perms['can_create']) ? 1 : 0;
            $canUpdate = !empty($perms['can_update']) ? 1 : 0;
            $canDelete = !empty($perms['can_delete']) ? 1 : 0;

            // Only insert if at least one permission is active
            if ($canRead || $canCreate || $canUpdate || $canDelete) {
                ModuleAccess::create([
                    'id'          => (string) Str::uuid(),
                    'm_role_id'   => $roleId,
                    'm_module_id' => $moduleId,
                    'can_read'    => $canRead,
                    'can_create'  => $canCreate,
                    'can_update'  => $canUpdate,
                    'can_delete'  => $canDelete,
                    'is_active'   => true,
                    'created_by'  => $userId,
                    'updated_by'  => $userId,
                ]);
            }
        }
    }
}
