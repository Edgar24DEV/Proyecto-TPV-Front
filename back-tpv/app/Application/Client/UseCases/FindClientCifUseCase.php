<?php

namespace App\Application\Client\UseCases;

use App\Application\Client\DTO\FindClientCifCommand;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;

class FindClientCifUseCase
{
    public function __construct(
        private readonly EloquentClientRepository $clientRepository,
        private readonly ClientService $clientService
    ) {}

    public function __invoke(FindClientCifCommand $command): Client
    {
        $client = $this->clientRepository->findByCif(
            $command->getCif(),
        );

        return $this->clientService->showClientSimpleInfo($client);
    }
}
