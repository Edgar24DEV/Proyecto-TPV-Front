<?php

namespace Controllers\Product;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\Handlers\AddOrderCommandHandler;
use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\DTO\AddProductRestaurantCommand;
use App\Application\Product\DTO\UpdateDeactivateProductCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Product\Handlers\AddProductCommandHandler;
use App\Application\Product\Handlers\AddProductRestaurantCommandHandler;
use App\Application\Product\Handlers\UpdateDeactivateProductCommandHandler;
use App\Application\Product\Handlers\UpdateProductRestaurantCommandHandler;
use App\Application\Product\UseCases\AddProductRestaurantUseCase;
use App\Application\Product\UseCases\AddProductUseCase;
use App\Application\Product\UseCases\UpdateDeactivateProductUseCase;
use App\Application\Product\UseCases\UpdateProductRestaurantUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class PutDeactivateProductController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly UpdateDeactivateProductUseCase $updateDeactivateProductUseCase,
        private readonly UpdateDeactivateProductCommandHandler $updateDeactivateProductCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {
            $command = new UpdateDeactivateProductCommand(
                $request->get("activo"),
                $request->get("id_producto"),
            );

            $result = $this->updateDeactivateProductCommandHandler->handle($command);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('product')->warning(
                "La validación falló al desactivar eñ producto{$request->input('id_producto')}\n" .
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
            return response()->json([
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        } catch (\InvalidArgumentException $e) {
            Log::channel('product')->warning(
                "Error en algún campo al desactivar el producto {$request->input('id_producto')}\n" .
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
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::channel('product')->error(
                "Error al desactivar el producto {$request->input('nombre')}\n" .
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
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

}