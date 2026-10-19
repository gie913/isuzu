<?php

namespace App\Services;

use App\Repositories\Sql\SqlBaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected SqlBaseRepository $repository;

    public function __construct(SqlBaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all active records.
     *
     * @return array
     */
    public function all(): array
    {
        $data = $this->repository->all();
        return $this->formatResponse(true, $data, 'Records retrieved successfully.');
    }

    /**
     * Get paginated active records.
     *
     * @param int $perPage
     * @param string|null $search
     * @param array $searchColumns
     * @param string $orderBy
     * @param string $orderDir
     * @param array $filters
     * @return array
     */
    public function paginate(
        int $perPage = 15,
        ?string $search = null,
        array $searchColumns = [],
        string $orderBy = 'created_at',
        string $orderDir = 'desc',
        array $filters = []
    ): array {
        $paginator = $this->repository->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir, $filters);
        return $this->formatResponse(true, $paginator, 'Paginated records retrieved successfully.');
    }

    /**
     * Find an active record by ID.
     *
     * @param string|int $id
     * @return array
     */
    public function find(string|int $id): array
    {
        $record = $this->repository->find($id);

        if (!$record) {
            return $this->formatResponse(false, null, 'Record not found.');
        }

        return $this->formatResponse(true, $record, 'Record retrieved successfully.');
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $record = $this->repository->create($data);
        return $this->formatResponse(true, $record, 'Record created successfully.');
    }

    /**
     * Update an existing record.
     *
     * @param string|int $id
     * @param array $data
     * @return array
     */
    public function update(string|int $id, array $data): array
    {
        $record = $this->repository->update($id, $data);

        if (!$record) {
            return $this->formatResponse(false, null, 'Record not found or update failed.');
        }

        return $this->formatResponse(true, $record, 'Record updated successfully.');
    }

    /**
     * Soft delete an existing record.
     *
     * @param string|int $id
     * @return array
     */
    public function delete(string|int $id): array
    {
        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            return $this->formatResponse(false, null, 'Record not found or delete failed.');
        }

        return $this->formatResponse(true, null, 'Record deleted successfully.');
    }

    /**
     * Module Code for authorization checks.
     *
     * @return string
     */
    abstract public function findModuleCode(): string;

    /**
     * Module Name for UI/display.
     *
     * @return string
     */
    abstract public function findModuleName(): string;

    /**
     * Standard response formatting.
     *
     * @param bool $success
     * @param mixed $data
     * @param string $message
     * @return array
     */
    protected function formatResponse(bool $success, mixed $data, string $message): array
    {
        return [
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ];
    }
}
