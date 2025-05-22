<?php

namespace Controllers\Product;

use App\Application\Employee\DTO\FindByIdEmployeeCommand;
use App\Application\Employee\Handlers\FindByIdEmployeeCommandHandler;
use App\Application\Employee\UseCases\FindByIdEmployeeUseCase;
use App\Application\Product\DTO\GetProductCommand;
use App\Application\Product\Handlers\GetProductCommandHandler;
use App\Application\Product\UseCases\GetProductByIdUseCase;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\ListEmployeesRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetProductByIdController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly GetProductByIdUseCase $getProductByIdUseCase,
        private readonly GetProductCommandHandler $getProductCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idProduct = new GetProductCommand(
                $request->input("id"),
            );

            $this->isNanId($idProduct->getId());

            $result = $this->getProductCommandHandler->handle($idProduct);


            return response()->json($result);

        } catch (\Exception $e) {
            Log::channel('product')->error(
                "Error al listar el producto {$request->input('id')}\n" .
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
