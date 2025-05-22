<?php

namespace Controllers\Order;


use App\Application\Order\DTO\UpdateOrderStatusCommand;
use App\Application\Order\Handlers\UpdateOrderStatusCommandHandler;
use App\Application\Order\UseCases\UpdateOrderStatusUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PutOrderStatusController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UpdateOrderStatusUseCase $updateOrderStatusUseCase,
        private readonly UpdateOrderStatusCommandHandler $updateOrderStatusCommandHandler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validamos los datos del request
            $this->validateOrFail($request);

            // Creamos el comando para actualizar el empleado
            $command = new UpdateOrderStatusCommand(
                $request->get("id"),
                $request->get("estado"),
            );

            // Procesamos el comando a través del handler
            $result = $this->updateOrderStatusCommandHandler->handle($command);

            // Devolvemos la respuesta con el empleado actualizado
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('order')->warning(
                "Fallo al actualizar el estado del pedido \n" .
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
            // En caso de error en la validación, respondemos con un error 422
            return response()->json([
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::channel('order')->error(
                "Fallo al actualizar el estado del pedido \n" .
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
            "estado" => "required|string|in:Abierto,Pendiente,Cancelado,Cerrado",
        ]);
    }

}
