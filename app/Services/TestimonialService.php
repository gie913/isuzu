<?php

namespace App\Services;

use App\Repositories\Sql\SqlTestimonialRepository;

class TestimonialService extends BaseService
{
    protected SqlTestimonialRepository $TestimonialRepository;

    public function __construct(SqlTestimonialRepository $TestimonialRepository)
    {
        parent::__construct($TestimonialRepository);
        $this->TestimonialRepository = $TestimonialRepository;
    }

    public function findModuleCode(): string
    {
        return 'testimonial';
    }

    public function findModuleName(): string
    {
        return 'Testimonials';
    }
}