<?php

namespace App\Services;

use App\Repositories\Sql\SqlProductRepository;

class ProductService extends BaseService
{
    protected SqlProductRepository $ProductRepository;

    public function __construct(SqlProductRepository $ProductRepository)
    {
        parent::__construct($ProductRepository);
        $this->ProductRepository = $ProductRepository;
    }

    public function findModuleCode(): string
    {
        return 'product';
    }

    public function findModuleName(): string
    {
        return 'Products';
    }
}