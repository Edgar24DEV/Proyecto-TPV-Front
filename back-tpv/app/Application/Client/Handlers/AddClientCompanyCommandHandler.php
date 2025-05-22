<?php

namespace App\Application\Client\Handlers;

use App\Application\Client\DTO\AddClientCompanyCommand;
use App\Application\Client\UseCases\AddClientCompanyUseCase;

class AddClientCompanyCommandHandler
{
    private AddClientCompanyUseCase $addClientCompanyUseCase;

    public function __construct(AddClientCompanyUseCase $addClientCompanyUseCase)
    {
        $this->addClientCompanyUseCase = $addClientCompanyUseCase;
    }

    public function handle(AddClientCompanyCommand $command)
    {
        return $this->addClientCompanyUseCase->__invoke($command);
    }
}
