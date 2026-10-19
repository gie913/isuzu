<?php

namespace App\Repositories\Sql;

use App\Models\CMSMenu;

class SqlCMSMenuRepository extends SqlBaseRepository
{
    public function __construct(CMSMenu $model)
    {
        parent::__construct($model);
    }

    /**
     * Find menu by its code/name.
     *
     * @param string $code
     * @return CMSMenu|null
     */
    public function findByCode(string $code): ?CMSMenu
    {
        return $this->model->newQuery()
            ->where(function ($query) use ($code) {
                $query->where('name', $code)
                    ->orWhere('id', $code);
            })
            ->where('is_active', true)
            ->first();
    }
}
