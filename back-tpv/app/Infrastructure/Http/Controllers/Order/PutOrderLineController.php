<?php

namespace Controllers\Order;

use App\Application\Employee\DTO\UpdateEmployeeCommand;
use App\Application\Employee\Handlers\UpdateEmployeeCommandHandler;
use App\Application\OrderLine\DTO\UpdateOrderLineCommand;
use App\Application\OrderLine\Handlers\UpdateOrderLineCommandHandler;
use App\Application\OrderLine\UseCases\UpdateOrderLineUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\UpdateEmployeeUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PutOrderLineController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UpdateOrderLineUseCase $updateOrderLineUseCase,
        private readonly UpdateOrderLineCommandHandler $updateOrderLineCommandHandler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validamos los datos del request
            $this->validateOrFail($request);

            // Creamos el comando para actualizar el empleado
            $command = new UpdateOrderLineCommand(
                $request->get("id"),
                $request->get("cantidad"),
                $request->get("nombre"),
                $request->get("precio"),
            );

            // Procesamos el comando a través del handler
            $result = $this->updateOrderLineCommandHandler->handle($command);

            // Validamos el resultado del caso de uso
            $this->validateOfUseCase($result);

            // Devolvemos la respuesta con el empleado actualizado
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // En caso de error en la validación, respondemos con un error 422
            Log::channel('order')->warning(
                "Fallo al actualizar la linea del pedido \n" .
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
        } catch (\Exception $e) {
            Log::channel('order')->error(
                "Fallo al actualizar la linea del pedido \n" .
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
            // En caso de error general, devolvemos un error 500
            return response()->json([
                'error' => 'Hubo un error al procesar la solicitud.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function validateOrFail(Request $request): void
    {
        // Validamos los datos requeridos en el request
        $request->validate([
            "id" => "required|integer",
            "nombre" => "required|string|max:255",
            "cantidad" => "required|integer",
            "precio" => "required|decimal:2,2",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        // Si el resultado no es válido, respondemos con un error
        if (is_null($result) || $result->id < 1) {
            $this->apiError("No se ha podido crear o actualizar el producto.");
        }
    }
}
