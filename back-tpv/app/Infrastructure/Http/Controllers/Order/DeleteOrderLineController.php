<?php

namespace Controllers\Order;

use App\Application\OrderLine\DTO\DeleteOrderLineCommand;
use App\Application\OrderLine\Handlers\DeleteOrderLineCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\OrderLine\UseCases\DeleteOrderLineUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class DeleteOrderLineController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteOrderLineUseCase $deleteOrderLineUseCase,
        private readonly DeleteOrderLineCommandHandler $deleteOrderLineCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        $id = new DeleteOrderLineCommand(
            $request->input("id"),
        );

        try {
            $this->isNanId($id->getId());
            $result = $this->deleteOrderLineCommandHandler->handle($id);
            return response()->json("Borrado con exito");
        } catch (\InvalidArgumentException $e) {
            Log::channel('order')->warning(
                "Fallo al borrar la linea de pedido con ID: {$request->input("id")} \n" .
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
                "Fallo al borrar la linea de pedido con ID: {$request->input("id")} \n" .
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