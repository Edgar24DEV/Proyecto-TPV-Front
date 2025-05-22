<?php

namespace Controllers\Category;

use App\Application\Category\DTO\UpdateActiveCategoryCommand;
use App\Application\Category\Handlers\UpdateActiveCategoryCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Category\UseCases\UpdateActiveCategoryUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PutActiveCategoryController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UpdateActiveCategoryUseCase $updateActiveCategoryUseCase,
        private readonly UpdateActiveCategoryCommandHandler $updateActiveCategoryCommandHandler
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validamos los datos del request
            $this->validateOrFail($request);

            // Creamos el comando para actualizar el empleado
            $command = new UpdateActiveCategoryCommand(
                (int) $request->get("id"),
                $request->get("categoria"),
                $request->get("activo"),
                $request->get("id_empresa")
            );

            // Procesamos el comando a través del handler
            $result = $this->updateActiveCategoryCommandHandler->handle($command);

            if ($result->getId() === -1) {
                return response()->json([
                    'error' => 'No se pudo autenticar el empleado. Verifica tus credenciales.'
                ], 401);
            }

            // Validamos el resultado del caso de uso
            $this->validateOfUseCase($result);

            // Devolvemos la respuesta con el empleado actualizado
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // En caso de error en la validación, respondemos con un error 422
            Log::channel('product')->warning(
                "Parámetros no válidos en la petición: {$request} \n" .
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
            // En caso de error general, devolvemos un error 500
            Log::channel('product')->warning(
                "Error al procesar la solicitud: {$request} \n" .
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
            "categoria" => "required|string|max:255",
            "activo" => "required|in:0,1",
            "id_empresa" => "required|integer",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        // Si el resultado no es válido, respondemos con un error
        if (is_null($result) || $result->getId() < 1) {
            $this->apiError("No se ha podido actualizar la categoria.");
        }
    }
}
