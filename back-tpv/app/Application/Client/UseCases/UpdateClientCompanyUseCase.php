<?php

namespace App\Application\Client\UseCases;

use App\Application\Client\DTO\UpdateClientCompanyCommand;
use App\Domain\Company\Entities\Client;
use App\Domain\Company\Services\ClientService;
use App\Infrastructure\Repositories\EloquentClientRepository;
use App\Infrastructure\Repositories\EloquentCompanyRepository;
use Illuminate\Support\Facades\Log;

class UpdateClientCompanyUseCase
{
    public function __construct(
        private readonly EloquentClientRepository $clientRepository,
        private readonly EloquentCompanyRepository $companyRepository,
        private readonly ClientService $clientService,
    ) {
    }

    public function __invoke(UpdateClientCompanyCommand $command)
    {
        try {
            $this->validateOrFail($command);

            $updatedClient = $this->clientRepository->update($command);

            if (!$updatedClient) {
                throw new \RuntimeException("No se pudo actualizar el cliente o el cliente no existe");
            }

            return $this->clientService->showClientSimpleInfo($updatedClient);
        } catch (\Exception $e) {

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

    private function validateOrFail(UpdateClientCompanyCommand $command): void
    {
        if (!$this->clientRepository->exist($command->getId())) {
            throw new \Exception("El cliente no existe");
        }

        if ($command->getIdEmpresa() && !$this->companyRepository->exist($command->getIdEmpresa())) {
            throw new \Exception("La empresa no existe");
        }
    }
}