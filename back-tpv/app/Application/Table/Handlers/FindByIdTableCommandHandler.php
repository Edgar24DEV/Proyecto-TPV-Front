<?php
// UpdateTableHandler.php
namespace App\Application\Table\Handlers;

use App\Application\Table\DTO\FindByIdTableCommand;
use App\Application\Table\UseCases\FindByIdTableUseCase;
use App\Domain\Restaurant\Entities\Table;

class FindByIdTableCommandHandler
{
    private FindByIdTableUseCase $findByIdTableUseCase;
    public function __construct(FindByIdTableUseCase $findByIdTableUseCase)
    {
        $this->findByIdTableUseCase = $findByIdTableUseCase;
    }

    public function handle(FindByIdTableCommand $command): Table
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->findByIdTableUseCase->__invoke($command);
    }

}