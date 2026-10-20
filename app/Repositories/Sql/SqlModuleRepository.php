<?php

namespace App\Repositories\Sql;

use App\Models\Module;

class SqlModuleRepository extends SqlBaseRepository
{
    public function __construct(Module $model)
    {
        parent::__construct($model);
    }

    /**
     * Find menu by its code/name.
     *
     * @param string $code
     * @return Module|null
     */
    public function findByCode(string $code): ?Module
    {
        return $this->model->newQuery()
            ->where(function ($query) use ($code) {
                $query->where('name', $code)
                    ->orWhere('id', $code);
            })
            ->where('is_active', true)
            ->first();
    }

    /**
     * Find module by ID with parent, creator, and updater relationships.
     *
     * @param string|int $id
     * @return Module|null
     */
    public function find(string|int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model->newQuery()
            ->with(['parent', 'creator', 'updater'])
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get full tree of modules starting from roots (parent_id is null).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTree(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->newQuery()
            ->with(['children' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_number', 'asc')
                    ->with(['children' => function ($subQuery) {
                        $subQuery->where('is_active', true)
                            ->orderBy('sort_number', 'asc');
                    }]);
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_number', 'asc')
            ->get();
    }
}
