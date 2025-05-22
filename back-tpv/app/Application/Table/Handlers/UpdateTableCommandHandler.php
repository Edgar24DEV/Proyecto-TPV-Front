<?php
// UpdateTableHandler.php
namespace App\Application\Table\Handlers;

use App\Application\Table\DTO\UpdateTableCommand;
use App\Application\Table\UseCases\UpdateTableUseCase;
use App\Domain\Restaurant\Entities\Table;

class UpdateTableCommandHandler
{
    private UpdateTableUseCase $updateTableUseCase;


    public function __construct(UpdateTableUseCase $updateTableUseCase)
    {
        $this->updateTableUseCase = $updateTableUseCase;
    }

    public function handle(UpdateTableCommand $command): Table
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateTableUseCase->__invoke($command);
    }
/*
    private function isTableAllowedToUpdate(int $tableId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }*/
}