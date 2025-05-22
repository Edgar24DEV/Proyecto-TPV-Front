<?php

namespace App\Application\Client\Handlers;

use App\Application\Client\DTO\DeleteClientCompanyCommand;
use App\Application\Client\UseCases\DeleteClientCompanyUseCase;

class DeleteClientCompanyCommandHandler
{
    private  $deleteClientCompanyUseCase;

    public function __construct(DeleteClientCompanyUseCase $deleteClientCompanyUseCase)
    {
        $this->deleteClientCompanyUseCase = $deleteClientCompanyUseCase;
    }

    public function handle(DeleteClientCompanyCommand $command)
    {
        return $this->deleteClientCompanyUseCase->__invoke($command);
    }
}
