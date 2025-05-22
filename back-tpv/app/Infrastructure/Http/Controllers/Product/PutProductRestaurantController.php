<?php

namespace Controllers\Product;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\Handlers\AddOrderCommandHandler;
use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\DTO\AddProductRestaurantCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Product\Handlers\AddProductCommandHandler;
use App\Application\Product\Handlers\AddProductRestaurantCommandHandler;
use App\Application\Product\Handlers\UpdateProductRestaurantCommandHandler;
use App\Application\Product\UseCases\AddProductRestaurantUseCase;
use App\Application\Product\UseCases\AddProductUseCase;
use App\Application\Product\UseCases\UpdateProductRestaurantUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;



class PutProductRestaurantController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly UpdateProductRestaurantUseCase $updateProductRestaurantUseCase,
        private readonly UpdateProductRestaurantCommandHandler $updateProductRestaurantCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        try {
            $command = new UpdateProductRestaurantCommand(
                $request->get("activo"),
                $request->get("id_producto"),
                $request->get("id_restaurante"),
            );

            $result = $this->updateProductRestaurantCommandHandler->handle($command);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('restaurant')->warning(
                "La validación falló al actualizar la relacion del restaurante {$request->input('id_restaurante')} con el producto {$request->input('id_producto')}\n" .
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
        }catch (\InvalidArgumentException $e) {
            Log::channel('restaurant')->warning(
                "Error en algún campo al actualizar la relacion del restaurante {$request->input('id_restaurante')} con el producto {$request->input('id_producto')}\n" .
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
        catch (\Exception $e) {
            Log::channel('restaurant')->error(
                "Error al actualizar la relacion del restaurante {$request->input('id_restaurante')} con el producto {$request->input('id_producto')}\n"  .
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