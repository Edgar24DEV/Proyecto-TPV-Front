<?php

namespace Controllers\Product;

use App\Application\Product\DTO\GetProductRestaurantCommand;
use App\Application\Product\DTO\ListProductsRestaurantCommand;
use App\Application\Product\DTO\UpdateProductRestaurantCommand;
use App\Application\Product\Handlers\GetProductsRestaurantCommandHandler;
use App\Application\Product\Handlers\ListProductsRestaurantCommandHandler;
use App\Application\Product\UseCases\GetProductsRestaurantUseCase;
use Illuminate\Http\Request;
use App\Application\Product\UseCases\ListProductsRestaurantUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetProductRestaurantController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly GetProductsRestaurantUseCase $listProductsRestaurantUseCase,
        private readonly GetProductsRestaurantCommandHandler $getProductRestaurantCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {
            $command = new GetProductRestaurantCommand(
                $request->get("id_producto"),
                $request->get("id_restaurante"),
            );

            $result = $this->getProductRestaurantCommandHandler->handle($command);
            return response()->json($result->getActivo());

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('restaurant')->warning(
                "La validación falló al obtener la relacion del restaurante {$request->input('id_restaurante')} con el producto {$request->input('id_producto')}\n" .
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
                "Error en algún campo al obtener la relacion del restaurante {$request->input('id_restaurante')} con el producto {$request->input('id_producto')}\n" .
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
                "Error al obtener la relacion del restaurante {$request->input('id_restaurante')} con el producto {$request->input('id_producto')}\n"  .
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

