<?php

namespace App\Repositories\Sql;

use App\Models\Content;

class SqlContentRepository extends SqlBaseRepository
{
    public function __construct(Content $model)
    {
        parent::__construct($model);
    }

    /**
     * Find record by ID with creator and updater relationships.
     *
     * @param string|int $id
     * @return Content|null
     */
    public function find(string|int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model->newQuery()
            ->with(['creator', 'updater'])
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }
}