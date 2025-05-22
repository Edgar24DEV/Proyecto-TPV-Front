<?php

namespace App\Application\OrderLine\Handlers;

use App\Application\OrderLine\DTO\AddOrderLineCommand;
use App\Application\OrderLine\UseCases\AddOrderLineUseCase;
use App\Domain\Order\Entities\OrderLine;
use Illuminate\Support\Collection;

class AddOrderLineCommandHandler
{
    public function __construct(
        private readonly AddOrderLineUseCase $addOrderLineUseCase,
    ) {
    }

    public function handle(AddOrderLineCommand $command): OrderLine
    {
        return $this->addOrderLineUseCase->__invoke($command);
    }
}
