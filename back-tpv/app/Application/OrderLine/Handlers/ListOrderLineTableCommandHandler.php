<?php

namespace App\Application\OrderLine\Handlers;

use App\Application\OrderLine\DTO\ListOrderLineTableCommand;
use App\Application\OrderLine\UseCases\ListOrderLineTableUseCase;
use Illuminate\Support\Collection;

class ListOrderLineTableCommandHandler
{
    public function __construct(
        private readonly ListOrderLineTableUseCase $listOrderLineTableUseCase
    ) {}

    public function handle(ListOrderLineTableCommand $command): Collection
    {
        return $this->listOrderLineTableUseCase->__invoke($command);
    }
}
