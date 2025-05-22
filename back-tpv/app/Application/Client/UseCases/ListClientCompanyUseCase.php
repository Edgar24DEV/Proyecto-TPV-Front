<?php

namespace App\Application\Client\UseCases;

use App\Application\Client\DTO\ListClientCompanyCommand;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use Illuminate\Support\Collection;

final class ListClientCompanyUseCase
{
    public function __construct(
        private readonly EloquentClientRepository $clientRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly ClientService $clientService,
    ) {}

    public function __invoke(ListClientCompanyCommand $command): Collection
    {
        $idCompany =  $command->getIdCompany();
        
        $this->validateOrFail($idCompany);
        $clients = $this->clientRepository->findByCompany($idCompany);
        $clients = $this->clientService->showClientInfo($clients);
        return $clients;
    }


    private function validateOrFail(int $idCompany): void
    {
        if ($idCompany <= 0) {
            throw new \InvalidArgumentException("ID inválido");
        }

        if (!$this->companyRepository->exist($idCompany)) {
            throw new \InvalidArgumentException("ID No existe");
        }

    }
}