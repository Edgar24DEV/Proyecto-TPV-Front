<?php
// UpdateEmployeeHandler.php
namespace App\Application\Table\Handlers;

use App\Application\Table\DTO\ListTablesRestaurantCommand;
use App\Application\Table\UseCases\ListTablesRestaurantUseCase;
use Illuminate\Support\Collection;

class ListTablesRestaurantCommandHandler
{
    private ListTablesRestaurantUseCase $listTablesRestaurantUseCase;
    public function __construct(ListTablesRestaurantUseCase $listTablesRestaurantUseCase)
    {
        $this->listTablesRestaurantUseCase = $listTablesRestaurantUseCase;
    }

    public function handle(ListTablesRestaurantCommand $command): Collection
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->listTablesRestaurantUseCase->__invoke($command);
    }

}