<?php

namespace App\Services;

use App\Repositories\Sql\SqlUploadSetupRepository;

class UploadSetupService extends BaseService
{
    protected SqlUploadSetupRepository $UploadSetupRepository;

    public function __construct(SqlUploadSetupRepository $UploadSetupRepository)
    {
        parent::__construct($UploadSetupRepository);
        $this->UploadSetupRepository = $UploadSetupRepository;
    }

    public function findModuleCode(): string
    {
        return 'upload_setup';
    }

    public function findModuleName(): string
    {
        return 'Upload setups';
    }

    /**
     * Find upload setup by module ID/code and field name.
     *
     * @param string|null $moduleId
     * @param string $name
     * @return array
     */
    public function findByModuleIdByName(?string $moduleId, string $name): array
    {
        $setup = $this->UploadSetupRepository->findByModuleIdByName($moduleId, $name);

        if (!$setup) {
            return $this->formatResponse(false, null, 'Upload setup not found.');
        }

        return $this->formatResponse(true, $setup, 'Upload setup retrieved successfully.');
    }
}