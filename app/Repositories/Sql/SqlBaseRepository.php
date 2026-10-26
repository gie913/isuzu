<?php

namespace App\Repositories\Sql;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

abstract class SqlBaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all active records.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get paginated active records with search, filter, and ordering.
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $searchColumns
     * @param string $orderBy
     * @param string $orderDir
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(
        int $perPage = 15,
        ?string $search = null,
        array $searchColumns = [],
        string $orderBy = 'created_at',
        string $orderDir = 'desc',
        array $filters = []
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        $query = $this->model->newQuery()->where('is_active', true);

        if ($search && !empty($searchColumns)) {
            $query->where(function ($q) use ($search, $searchColumns) {
                foreach ($searchColumns as $col) {
                    $q->orWhere($col, 'LIKE', '%' . $search . '%');
                }
            });
        }

        foreach ($filters as $col => $val) {
            if ($col !== 'is_active' && $val !== null && $val !== '' && $val !== 'all') {
                $query->where($col, $val);
            }
        }

        return $query->orderBy($orderBy, $orderDir)->paginate($perPage);
    }

    /**
     * Find an active record by ID.
     *
     * @param string|int $id
     * @return Model|null
     */
    public function find(string|int $id): ?Model
    {
        return $this->model->newQuery()
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Create a new model instance.
     * Auto-generates UUID v1/v4 and sets created_by.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        if (empty($attributes['id'])) {
            $attributes['id'] = (string) Str::uuid();
        }

        if (empty($attributes['created_by'])) {
            $attributes['created_by'] = Auth::check() ? (string) Auth::id() : 'system';
        }

        if (!isset($attributes['is_active'])) {
            $attributes['is_active'] = true;
        }

        return $this->model->newQuery()->create($attributes);
    }

    /**
     * Update an existing model.
     * Sets updated_by.
     *
     * @param string|int $id
     * @param array $attributes
     * @return Model|null
     */
    public function update(string|int $id, array $attributes): ?Model
    {
        $record = $this->find($id);

        if (!$record) {
            return null;
        }

        if (Auth::check()) {
            $attributes['updated_by'] = (string) Auth::id();
        }

        $record->update($attributes);

        return $record;
    }

    /**
     * Soft delete a model by setting is_active = 0.
     *
     * @param string|int $id
     * @return bool
     */
    public function delete(string|int $id): bool
    {
        $record = $this->find($id);

        if (!$record) {
            return false;
        }

        $updateData = ['is_active' => false];
        if (Auth::check()) {
            $updateData['updated_by'] = (string) Auth::id();
        }

        return (bool) $record->update($updateData);
    }

    /**
     * Hard delete a model from the database.
     *
     * @param string|int $id
     * @return bool
     */
    public function hardDelete(string|int $id): bool
    {
        $record = $this->model->newQuery()->find($id);

        if (!$record) {
            return false;
        }

        return (bool) $record->delete();
    }

    /**
     * Get table column names for the model.
     *
     * @return array
     */
    public function getTableColumns(): array
    {
        return $this->model->getConnection()->getSchemaBuilder()->getColumnListing($this->model->getTable());
    }
}
