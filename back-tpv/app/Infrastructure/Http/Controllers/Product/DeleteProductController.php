<?php

namespace Controllers\Product;

use App\Application\Product\Handlers\DeleteProductCommandHandler;
use App\Application\Payment\DTO\DeletePaymentCommand;
use App\Application\Payment\Handlers\DeletePaymentCommandHandler;
use App\Application\Payment\UseCases\DeletePaymentUseCase;
use App\Application\Product\DTO\DeleteProductCommand;
use App\Application\Product\UseCases\DeleteProductUseCase;
use App\Application\Role\DTO\DeleteRoleCommand;
use App\Application\Role\Handlers\DeleteRoleCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class DeleteProductController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteProductUseCase $deleteProductUseCase,
        private readonly DeleteProductCommandHandler $deleteProductCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        $id = new DeleteProductCommand(
            (int) $request->input("id"),
        );

        try {
            $this->isNanId($id->getId());
            $result = $this->deleteProductCommandHandler->handle($id);
            if (!$result) {
                return response()->json([
                    'error' => "Error al intentar borrar"
                ], 404);
            }
            return response()->json("Borrado con exito");
        } catch (\InvalidArgumentException $e) {
            Log::channel('product')->warning(
                "Error en algún campo al borrar el producto {$request->input('id')}\n" .
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
                "Error al borrar el producto {$request->input('id')}\n" .
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