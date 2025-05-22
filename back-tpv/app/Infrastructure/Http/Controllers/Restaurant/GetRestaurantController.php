<?php

namespace Controllers\Restaurant;

use App\Application\Restaurant\DTO\FindRestaurantCommand;
use App\Application\Restaurant\Handlers\FindRestaurantCommandHandler;
use App\Application\Table\DTO\RestaurantCommand;
use App\Application\Table\Handlers\RestaurantCommandHandler;
use Illuminate\Http\Request;
use App\Application\Table\UseCases\RestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetRestaurantController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly FindRestaurantCommand $RestaurantUseCase,
        private readonly FindRestaurantCommandHandler $RestaurantCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idRestaurant = $request->input("id_restaurante");

            $this->isNanId($idRestaurant);

            $command = new FindRestaurantCommand(
                $idRestaurant
            );

            $result = $this->RestaurantCommandHandler->handle($command);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('restaurant')->warning(
                "No se pudo obtener el restaurante \n" .
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
    }
}
