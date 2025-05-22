<?php

namespace Controllers\Location;

use App\Application\Location\DTO\UpdateActiveLocationCommand;
use App\Application\Location\Handlers\UpdateActiveLocationCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\Location\UseCases\UpdateActiveLocationUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PutActiveLocationController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly UpdateActiveLocationUseCase $updateActiveLocationUseCase,
        private readonly UpdateActiveLocationCommandHandler $updateActiveLocationCommandHandler
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validamos los datos del request
            $this->validateOrFail($request);

            // Creamos el comando para actualizar el empleado
            $command = new UpdateActiveLocationCommand(
                (int) $request->get("id"),
                $request->get("ubicacion"),
                $request->get("activo"),
                $request->get("id_restaurante")
            );

            // Procesamos el comando a través del handler
            $result = $this->updateActiveLocationCommandHandler->handle($command);

            if ($result->getId() === -1) {
                return response()->json([
                    'error' => 'No se pudo autenticar la ubicación. Verifica tus credenciales.'
                ], 401);
            }

            // Validamos el resultado del caso de uso
            $this->validateOfUseCase($result);

            // Devolvemos la respuesta con el empleado actualizado
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('restaurant')->warning(
                "La validación falló  al cambiar el estado en la ubicación {$request->input('id')}\n" .
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
        }catch (\InvalidArgumentException $e) {
            Log::channel('restaurant')->warning(
                "Error en algún campo al cambiar el estado en la ubicación {$request->input('id')}\n" .
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
        catch (\Exception $e) {
            Log::channel('restaurant')->error(
                "Error al cambiar el estado en la ubicación {$request->input('id')}\n"  .
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
            "ubicacion" => "required|string|max:255",
            "activo" => "required|in:0,1",
            "id_restaurante" => "required|integer",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        // Si el resultado no es válido, respondemos con un error
        if (is_null($result) || $result->getId() < 1) {
            $this->apiError("No se ha podido actualizar la ubicación.");
        }
    }
}
