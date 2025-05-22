<?php
// UpdateEmployeeHandler.php
namespace App\Application\Category\Handlers;

use App\Application\Category\DTO\GetCategoryCompanyCommand;
use App\Application\Category\DTO\ListCategoryCommand;
use App\Application\Category\UseCases\GetCategoryCompanyUseCase;
use App\Application\Category\UseCases\ListCategoryUseCase;
use App\Domain\Product\Entities\Category;
use Illuminate\Support\Collection;

class GetCategoryCommandHandler
{
    private GetCategoryCompanyUseCase $getCategoryCompanyUseCase;
    public function __construct(GetCategoryCompanyUseCase $getCategoryCompanyUseCase)
    {
        $this->getCategoryCompanyUseCase = $getCategoryCompanyUseCase;
    }

    public function handle(GetCategoryCompanyCommand $command): Category
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->getCategoryCompanyUseCase->__invoke($command);
    }

}