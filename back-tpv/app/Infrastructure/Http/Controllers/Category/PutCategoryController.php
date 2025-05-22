<?php

namespace Controllers\Category;

use App\Application\Category\DTO\UpdateCategoryCommand;
use App\Application\Category\Handlers\UpdateCategoryCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Category\UseCases\UpdateCategoryUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PutCategoryController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UpdateCategoryUseCase $updateCategoryUseCase,
        private readonly UpdateCategoryCommandHandler $updateCategoryCommandHandler
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validamos los datos del request
            $this->validateOrFail($request);

            // Creamos el comando para actualizar el empleado
            $command = new UpdateCategoryCommand(
                (int) $request->get("id"),
                $request->get("categoria"),
                $request->get("activo"),
                $request->get("id_empresa")
            );

            // Procesamos el comando a través del handler
            $result = $this->updateCategoryCommandHandler->handle($command);

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
            // En caso de error en la validación, respondemos con un error 422
            return response()->json([
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // En caso de error general, devolvemos un error 500
            Log::channel('product')->warning(
                "Error al procesar la solicitud:{$request->input('id')} \n" .
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
