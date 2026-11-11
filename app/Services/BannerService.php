<?php

namespace App\Services;

use App\Repositories\Sql\SqlBannerRepository;

class BannerService extends BaseService
{
    protected SqlBannerRepository $BannerRepository;

    public function __construct(SqlBannerRepository $BannerRepository)
    {
        parent::__construct($BannerRepository);
        $this->BannerRepository = $BannerRepository;
    }

    public function findModuleCode(): string
    {
        return 'banner';
    }

    public function findModuleName(): string
    {
        return 'Banners';
    }
}