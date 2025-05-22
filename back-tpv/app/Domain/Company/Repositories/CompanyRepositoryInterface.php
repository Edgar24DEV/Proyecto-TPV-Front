<?php

namespace App\Domain\Company\Repositories;

interface CompanyRepositoryInterface
{
    public function exist(int $idCompany): bool;
    
}