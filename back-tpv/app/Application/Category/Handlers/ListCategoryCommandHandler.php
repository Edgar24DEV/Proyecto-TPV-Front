<?php
// UpdateEmployeeHandler.php
namespace App\Application\Category\Handlers;

use App\Application\Category\DTO\ListCategoryCommand;
use App\Application\Category\UseCases\ListCategoryUseCase;
use Illuminate\Support\Collection;

class ListCategoryCommandHandler
{
    private ListCategoryUseCase $listCategoryUseCase;
    public function __construct(ListCategoryUseCase $listCategoryUseCase)
    {
        $this->listCategoryUseCase = $listCategoryUseCase;
    }

    public function handle(ListCategoryCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listCategoryUseCase->__invoke($command);
    }

}