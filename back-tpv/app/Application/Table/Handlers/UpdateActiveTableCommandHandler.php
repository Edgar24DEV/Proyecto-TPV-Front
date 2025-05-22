<?php
// UpdateActiveTableHandler.php
namespace App\Application\Table\Handlers;

use App\Application\Table\DTO\UpdateActiveTableCommand;
use App\Application\Table\UseCases\UpdateActiveTableUseCase;
use App\Domain\Restaurant\Entities\Table;

class UpdateActiveTableCommandHandler
{
    private UpdateActiveTableUseCase $updateActiveTableUseCase;


    public function __construct(UpdateActiveTableUseCase $updateActiveTableUseCase)
    {
        $this->updateActiveTableUseCase = $updateActiveTableUseCase;
    }

    public function handle(UpdateActiveTableCommand $command): Table
    {   
        
        // Ahora, el handler pasa el DTO al caso de uso
        return $this->updateActiveTableUseCase->__invoke($command);
    }

    private function isTableAllowedToUpdate(int $tableId): bool
    {
        // Lógica ficticia para verificar si el empleado tiene permisos para actualizar
        return true;
    }
}