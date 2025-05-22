<?php
// UpdateEmployeeHandler.php
namespace App\Application\Category\Handlers;

use App\Application\Category\DTO\ListCategoryCommand;
use App\Application\Category\DTO\ListCategoryCompanyCommand;
use App\Application\Category\UseCases\ListCategoryCompanyUseCase;
use App\Application\Category\UseCases\ListCategoryUseCase;
use Illuminate\Support\Collection;

class ListCategoryCompanyCommandHandler
{
    private ListCategoryCompanyUseCase $listCategoryUseCase;
    public function __construct(ListCategoryCompanyUseCase $listCategoryUseCase)
    {
        $this->listCategoryUseCase = $listCategoryUseCase;
    }

    public function handle(ListCategoryCompanyCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listCategoryUseCase->__invoke($command);
    }

}