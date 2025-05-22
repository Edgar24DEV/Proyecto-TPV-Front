<?php

namespace App\Application\OrderLine\Handlers;

use App\Application\OrderLine\DTO\AddOrderLineCommand;
use App\Application\OrderLine\DTO\UpdateOrderLineCommand;
use App\Application\OrderLine\UseCases\AddOrderLineUseCase;
use App\Application\orderLine\UseCases\UpdateOrderLineUseCase;
use App\Domain\Order\Entities\OrderLine;
use Illuminate\Support\Collection;

class UpdateOrderLineCommandHandler
{
    public function __construct(
        private readonly UpdateOrderLineUseCase $updateOrderLineUseCase,
    ) {
    }

    public function handle(UpdateOrderLineCommand $command): OrderLine
    {
        return $this->updateOrderLineUseCase->__invoke($command);
    }
}
