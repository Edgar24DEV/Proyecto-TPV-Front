<?php
namespace App\Application\Table\Handlers;

use App\Application\Table\DTO\DeleteTableCommand;
use App\Application\Table\UseCases\DeleteTableUseCase;


class DeleteTableCommandHandler
{
    private DeleteTableUseCase $deleteTableUseCase;


    public function __construct(DeleteTableUseCase $deleteTableUseCase)
    {
        $this->deleteTableUseCase = $deleteTableUseCase;
    }

    public function handle(DeleteTableCommand $command): bool
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->deleteTableUseCase->__invoke($command);
    }

}