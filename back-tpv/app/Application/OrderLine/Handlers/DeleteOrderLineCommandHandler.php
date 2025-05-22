<?php
// UpdateOrderLineHandler.php
namespace App\Application\OrderLine\Handlers;

use App\Application\OrderLine\DTO\DeleteOrderLineCommand;
use App\Application\OrderLine\UseCases\DeleteOrderLineUseCase;


class DeleteOrderLineCommandHandler
{
    private DeleteOrderLineUseCase $deleteOrderLineUseCase;


    public function __construct(DeleteOrderLineUseCase $deleteOrderLineUseCase)
    {
        $this->deleteOrderLineUseCase = $deleteOrderLineUseCase;
    }

    public function handle(DeleteOrderLineCommand $command): bool
    {
        return $this->deleteOrderLineUseCase->__invoke($command);
    }

}