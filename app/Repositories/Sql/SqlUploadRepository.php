<?php

namespace App\Repositories\Sql;

use App\Models\Upload;

class SqlUploadRepository extends SqlBaseRepository
{
    public function __construct(Upload $model)
    {
        parent::__construct($model);
    }

    /**
     * Find record by ID with creator and updater relationships.
     *
     * @param string|int $id
     * @return Upload|null
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
     * Find upload by path.
     *
     * @param string $path
     * @return Upload|null
     */
    public function findByPath(string $path): ?Upload
    {
        return $this->model->newQuery()
            ->where('path', $path)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Find upload by file_source.
     *
     * @param string $fileSource
     * @return Upload|null
     */
    public function findByFileSource(string $fileSource): ?Upload
    {
        return $this->model->newQuery()
            ->where('file_source', $fileSource)
            ->where('is_active', true)
            ->first();
    }
}