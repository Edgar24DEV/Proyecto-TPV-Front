<?php

namespace App\Application\Client\UseCases;

use App\Application\Client\DTO\AddClientCompanyCommand;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Repositories\EloquentClientRepository;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;

class AddClientCompanyUseCase
{
    use ApiResponseTrait;

    public function __construct(
        private readonly EloquentClientRepository $clientRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly ClientService $clientService,
    ) {
    }

    public function __invoke(AddClientCompanyCommand $command)
    {
        try {
            $this->validateOrFail($command->getIdEmpresa());
            $client = $this->clientRepository->create($command);
            return $this->clientService->showClientSimpleInfo($client);
        } catch (\Exception $e) {
            // En caso de error, retornamos un cliente con valores predeterminados
            return new Client(
                id: -1,
                razon_social: null,
                direccion_fiscal: '',
                cif: '',
                correo: '',
                id_empresa: -1,
            );
        }
    }

    private function validateOrFail(?int $idEmpresa): void
    {
        if ($idEmpresa === null || $idEmpresa <= 0 || !$this->companyRepository->exist($idEmpresa)) {
            throw new \Exception("ID de empresa inválido");
        }
    }
}
