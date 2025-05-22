<?php

namespace Controllers\Restaurant;

use App\Application\Table\DTO\ListTablesRestaurantCommand;
use App\Application\Table\Handlers\ListTablesRestaurantCommandHandler;
use Illuminate\Http\Request;
use App\Application\Table\UseCases\ListTablesRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetListTablesRestaurantController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListTablesRestaurantUseCase $listTablesRestaurantUseCase,
        private readonly ListTablesRestaurantCommandHandler $listTablesRestaurantCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idRestaurant = $request->input("id_restaurante");

            $this->isNanId($idRestaurant);

            $command = new ListTablesRestaurantCommand(
                $idRestaurant
            );

            $result = $this->listTablesRestaurantCommandHandler->handle($command);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('restaurant')->warning(
                "No se pudieron obtener las mesas del restaurante \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                    ->take(3)
                    ->map(function ($trace, $i) {
                        return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                    })
                    ->implode("\n") . "\n"
            );
            return $this->apiError("" . $e->getMessage());
        }
    }

    private function isNanId($id)
    {
        if ($id === null || !is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }
        /*
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }*/
    }
}
