<?php

namespace App\Domain\Company\Repositories;

use App\Application\Client\DTO\AddClientCompanyCommand;
use App\Application\Client\DTO\UpdateClientCompanyCommand;
use App\Domain\Company\Entities\Client;

interface ClientRepositoryInterface
{
    public function exist(int $idCliente): bool;
    public function findByCompany(int $idCompany): array;
    public function create(AddClientCompanyCommand $command): Client;
    public function update(UpdateClientCompanyCommand $command): ?Client;
    public function find(int $id): ?Client;
    public function findByCif(string $cif): Client;
    public function softDelete(int $id): bool;
}
