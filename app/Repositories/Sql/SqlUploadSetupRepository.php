<?php

namespace App\Repositories\Sql;

use App\Models\UploadSetup;

class SqlUploadSetupRepository extends SqlBaseRepository
{
    public function __construct(UploadSetup $model)
    {
        parent::__construct($model);
    }

    /**
     * Find record by ID with creator and updater relationships.
     *
     * @param string|int $id
     * @return UploadSetup|null
     */
    public function find(string|int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model->newQuery()
            ->with(['creator', 'updater'])
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Find upload setup by module identifier (id or name/code) and field name.
     *
     * @param string|int|null $moduleId
     * @param string $name
     * @return UploadSetup|null
     */
    public function findByModuleIdByName($moduleId, string $name): ?UploadSetup
    {
        return $this->model->newQuery()
            ->where(function ($query) use ($moduleId) {
                $query->where('m_module_id', $moduleId);
                // Also check if module name was passed like 'user' or 'users'
                if ($moduleId === 'users') {
                    $query->orWhere('m_module_id', 'user');
                } elseif ($moduleId === 'user') {
                    $query->orWhere('m_module_id', 'users');
                }
            })
            ->where('name', $name)
            ->where('is_active', true)
            ->first();
    }
}