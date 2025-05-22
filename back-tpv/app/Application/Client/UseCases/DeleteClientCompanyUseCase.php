<?php

namespace App\Application\Client\UseCases;

use App\Application\Client\DTO\DeleteClientCompanyCommand;
use App\Application\Client\DTO\FindClientCifCommand;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;

class DeleteClientCompanyUseCase
{
    public function __construct(
        private readonly EloquentClientRepository $clientRepository,
        private readonly ClientService $clientService
    ) {}

    public function __invoke(DeleteClientCompanyCommand $command)   
    {
        $client = $this->clientRepository->find($command->getId());

        if (!$client) {
            throw new \Exception("Cliente no encontrado.");
        }

        return $this->clientRepository->softDelete($command->getId());
    }
}
