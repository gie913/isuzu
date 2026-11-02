<?php

namespace App\Services;

use App\Repositories\Sql\SqlUserRepository;

class UserService extends BaseService
{
    protected SqlUserRepository $userRepository;

    public function __construct(SqlUserRepository $userRepository)
    {
        parent::__construct($userRepository);
        $this->userRepository = $userRepository;
    }

    public function findModuleCode(): string
    {
        return 'users';
    }

    public function findModuleName(): string
    {
        return 'User Management';
    }

    public function create(array $data): array
    {
        if (isset($data['image_src'])) {
            $uploadService = app(\App\Services\UploadService::class);
            $data['image_src'] = $uploadService->finalizeUpload($data['image_src']);
        }

        return parent::create($data);
    }

    public function update(string|int $id, array $data): array
    {
        if (isset($data['image_src'])) {
            $uploadService = app(\App\Services\UploadService::class);
            $data['image_src'] = $uploadService->finalizeUpload($data['image_src']);
        }

        return parent::update($id, $data);
    }

    /**
     * Find user by username.
     *
     * @param string $username
     * @return array
     */
    public function findByUsername(string $username): array
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            return $this->formatResponse(false, null, 'User not found.');
        }

        return $this->formatResponse(true, $user, 'User retrieved successfully.');
    }

    /**
     * Find user by email.
     *
     * @param string $email
     * @return array
     */
    public function findByEmail(string $email): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return $this->formatResponse(false, null, 'User not found.');
        }

        return $this->formatResponse(true, $user, 'User retrieved successfully.');
    }

    /**
     * Find user by username or email.
     *
     * @param string $identifier
     * @return array
     */
    public function findByUsernameOrEmail(string $identifier): array
    {
        $user = $this->userRepository->findByUsernameOrEmail($identifier);

        if (!$user) {
            return $this->formatResponse(false, null, 'User not found.');
        }

        return $this->formatResponse(true, $user, 'User retrieved successfully.');
    }
}
