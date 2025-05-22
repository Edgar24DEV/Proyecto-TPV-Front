<?php

namespace Controllers\Employee;

use App\Application\Employee\DTO\UpdateEmployeeRoleCommand;
use App\Application\Employee\Handlers\UpdateEmployeeRoleCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Employee\UseCases\UpdateEmployeeRoleUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PutEmployeeRoleController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UpdateEmployeeRoleUseCase $updateEmployeeRoleUseCase,
        private readonly UpdateEmployeeRoleCommandHandler $updateEmployeeRoleCommandHandler
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validamos los datos del request
            $this->validateOrFail($request);
            // Creamos el comando para actualizar el empleado
            $command = new UpdateEmployeeRoleCommand(
                $request->get("id_usuario"),
                $request->get("id_rol")
            );

            // Procesamos el comando a través del handler
            $result = $this->updateEmployeeRoleCommandHandler->handle($command);

            if ($result->getId() === -1) {
                return response()->json([
                    'error' => 'Fallo al actualizar el rol del usuario.'
                ], 401);
            }

            $this->validateOfUseCase($result);

            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('employee')->error("Error en el envío de datos para actualizar rol \n" .
                "   Clase: " . __CLASS__ . "\n" .
                "   Mensaje: " . $e->getMessage() . "\n" .
                "   Línea: " . $e->getLine() . "\n" .
                "   Trace:\n" . collect($e->getTrace())
                ->take(3)
                ->map(function ($trace, $i) {
                    return "    #$i " . ($trace['file'] ?? '') . ':' . ($trace['line'] ?? '') . ' → ' . ($trace['function'] ?? '');
                })
                ->implode("\n") . "\n");
            // En caso de error en la validación, respondemos con un error 422
            return response()->json([
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // En caso de error general, devolvemos un error 500
            Log::channel('employee')->error("Error actualizando el rol del  empleado {$command->getId()}  \n" .
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
                'error' => 'Hubo un error al procesar la solicitud.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function validateOrFail(Request $request): void
    {
        // Validamos los datos requeridos en el request
        $request->validate([
            "id_usuario" => "required|integer|exists:empleados,id",
            "id_rol" => "required|integer|exists:rols,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        // Si el resultado no es válido, respondemos con un error
        if (is_null($result) || $result->getId() < 1) {
            $this->apiError("No se ha podido actualizar el empleado.");
        }
    }
}
