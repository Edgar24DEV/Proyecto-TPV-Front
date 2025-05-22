<?php

namespace App\Application\Client\Handlers;

use App\Application\Client\DTO\FindClientCifCommand;
use App\Application\Client\UseCases\FindClientCifUseCase;

class FindClientCifCommandHandler
{
    public function __construct(
        private readonly FindClientCifUseCase $findClientByCifUseCase
    ) {}

    public function handle(FindClientCifCommand $command)
    {
        return $this->findClientByCifUseCase->__invoke($command);
    }
}
