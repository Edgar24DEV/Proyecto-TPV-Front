<?php
namespace App\Application\Client\UseCases;
use App\Application\Client\DTO\FindByIdClientCommand;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;
use function PHPUnit\Framework\isNan;

final class FindByIdClientUseCase
{



    public function __construct(
        private readonly EloquentClientRepository $clientRepository,
        private readonly ClientService $clientService
    ) {
    }
    public function __invoke(FindByIdClientCommand $command): Client
    {
        $this->validateOrFail($command->getId());
        $client = $this->clientRepository->find($command->getId());
        $clientInfo = $this->clientService->showClientSimpleInfo($client);
        return $clientInfo;
    }


    private function validateOrFail(int $id): void
    {
        if ($id <= 0 || !$this->clientRepository->exist($id)) {
            throw new \Exception("ID cliente inválido");
        }
    }
}