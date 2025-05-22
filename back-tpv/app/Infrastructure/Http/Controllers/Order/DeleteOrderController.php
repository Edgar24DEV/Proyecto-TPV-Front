<?php

namespace Controllers\Order;

use App\Application\Order\DTO\DeleteOrderCommand;
use App\Application\Order\Handlers\DeleteOrderCommandHandler;
use App\Application\Order\UseCases\DeleteOrderUseCase;
use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Application\Payment\Handlers\DeletePaymentCommandHandler;
use App\Application\Payment\UseCases\DeletePaymentUseCase;
use App\Application\Role\DTO\DeleteRoleCommand;
use App\Application\Role\Handlers\DeleteRoleCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class DeleteOrderController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteOrderUseCase $deleteOrderUseCase,
        private readonly DeleteOrderCommandHandler $deleteOrderCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        $id = new DeleteOrderCommand(
            (int) $request->input("id"),
        );

        try {
            $this->isNanId($id->getId());
            $result = $this->deleteOrderCommandHandler->handle($id);
            if (!$result) {
                Log::channel('order')->warning("No se pudo borrar el pedido con ID: {$id->getId()}\n", [
                    '\n     class' => __CLASS__,
                    '\n\t line' => __LINE__,
                ]);
                return response()->json([
                    'error' => "Error al intentar borrar"
                ], 404);
            }
            return response()->json("Borrado con exito");
        } catch (\InvalidArgumentException $e) {
            Log::channel('order')->warning(
                "Fallo al borrar el pedido con ID: {$request->input('id')} \n" .
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
            ], 422);
        } catch (\Exception $e) {
            Log::channel('order')->error(
                "Fallo al borrar el pedido con ID: {$request->input('id')} \n" .
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

    private function isNanId($id)
    {
        if ($id === null || !is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException(
                $id <= 0 ? "El ID debe ser positivo" : "El ID debe ser numérico"
            );
        }
    }
}