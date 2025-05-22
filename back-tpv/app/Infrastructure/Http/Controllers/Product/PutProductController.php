<?php

namespace Controllers\Product;


use App\Application\Order\DTO\UpdateOrderDinersCommand;
use App\Application\Order\Handlers\UpdateOrderDinersCommandHandler;
use App\Application\Order\UseCases\UpdateOrderDinersUseCase;
use App\Application\Product\DTO\UpdateProductCommand;
use App\Application\Product\Handlers\UpdateProductCommandHandler;
use App\Application\Product\UseCases\UpdateProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PutProductController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UpdateProductUseCase $updateProductUseCase,
        private readonly UpdateProductCommandHandler $productCommandHandler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validamos los datos del request
            $this->validateOrFail($request);

            // Creamos el comando para actualizar el empleado
            $command = new UpdateProductCommand(
                $request->get("id"),
                $request->get("nombre"),
                $request->get("precio"),
                $request->get("imagen"),
                $request->get("activo"),
                $request->get("id_categoria"),
                $request->get("iva"),
            );

            // Procesamos el comando a través del handler
            $result = $this->productCommandHandler->handle($command);

            // Devolvemos la respuesta con el empleado actualizado
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('product')->warning(
                "La validación falló al actualizar un producto{$request->input('id')}\n" .
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
                "Error al actualizar el producto {$request->input('id')}\n" .
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
                "Error al actualizar el producto {$request->input('id')}\n" .
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

    private function validateOrFail(Request $request): void
    {
        // Validamos los datos requeridos en el request
        $request->validate([
            "id" => "required|integer",
            "nombre" => "required|string",
        ]);
    }

}
