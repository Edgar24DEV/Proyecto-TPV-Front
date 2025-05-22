<?php

namespace Controllers\Company;

use App\Application\Order\DTO\AddOrderCommand;
use App\Application\Order\Handlers\AddOrderCommandHandler;
use App\Application\Product\DTO\AddProductCommand;
use App\Application\Product\Handlers\AddProductCommandHandler;
use App\Application\Product\UseCases\AddProductUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Order\UseCases\AddOrderUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PostProductCompanyController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly AddProductUseCase $addProductUseCase,
        private readonly AddProductCommandHandler $addProductCommandHandler,
    ) {}


    public function __invoke(Request $request): JsonResponse
    {
        try {

            $this->validateOrFail($request);
            $command = new AddProductCommand(
                ucfirst(strtolower($request->get("nombre"))),
                (float) $request->get("precio"),
                $request->get("imagen"),
                $request->get("iva"),
                $request->get("id_categoria"),
                $request->get("id_empresa"),
            );

            $result = $this->addProductCommandHandler->handle($command);
            $this->validateOfUseCase($result);
            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('company')->error("Error al añadir un producto de la empresa {$command->getIdEmpresa()} \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                ->take(3)
                ->map(function ($trace, $i) {
                    return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                })
                ->implode("\n") . "\n");
            return response()->json([
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        } catch (\InvalidArgumentException $e) {
            Log::channel('product')->warning(
                "Error en algún campo al crear el producto para la compañia {$request->input('id_empresa')}\n" .
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
                "Error al crear el producto {$request->input('nombre')}\n" .
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

    private function validateOrFail($request): void
    {
        $request->validate([
            'nombre' => 'required|string',
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear el producto");
        }
    }
}
