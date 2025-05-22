<?php

namespace Controllers\Product;

use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Application\Product\Handlers\ListAllProductsRestaurantCommandHandler;
use App\Application\Product\Handlers\ListProductsRestaurantCommandHandler;
use App\Application\Product\UseCases\ListAllProductsRestaurantUseCase;
use Illuminate\Http\Request;
use App\Application\Product\UseCases\ListProductsRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetAllProductsRestaurantController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly ListAllProductsRestaurantUseCase $listProductsRestaurantUseCase,
        private readonly ListAllProductsRestaurantCommandHandler $listProductsRestaurantCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idRestaurant = $request->input("id_restaurante");

            $this->isNanId($idRestaurant);

            $command = new ListProductsRestaurantCommand(
                $idRestaurant
            );

            $result = $this->listProductsRestaurantCommandHandler->handle($command);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('product')->error(
                "Error al listar los productos visibles del restaurante {$request->input('id_restaurante')}\n" .
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
