<?php
// UpdateTableHandler.php
namespace App\Application\Table\Handlers;

use App\Application\Table\DTO\AddTableCommand;
use App\Application\Table\UseCases\AddTableUseCase;
use App\Domain\Restaurant\Entities\Table;

class AddTableCommandHandler
{
    private AddTableUseCase $addTableUseCase;


    public function __construct(AddTableUseCase $addTableUseCase)
    {
        $this->addTableUseCase = $addTableUseCase;
    }

    public function handle(AddTableCommand $command): Table
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->addTableUseCase->__invoke($command);
    }
/*
    private function isTableAllowedToUpdate(int $tableId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }*/
}