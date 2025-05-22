<?php

namespace App\Application\Client\Handlers;

use App\Application\Client\DTO\UpdateClientCompanyCommand;
use App\Application\Client\UseCases\UpdateClientCompanyUseCase;

class UpdateClientCompanyCommandHandler
{
    public function __construct(
        private readonly UpdateClientCompanyUseCase $updateClientCompanyUseCase
    ) {}

    public function handle(UpdateClientCompanyCommand $command)
    {
        return $this->updateClientCompanyUseCase->__invoke($command);
    }
}