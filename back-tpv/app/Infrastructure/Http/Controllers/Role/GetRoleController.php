<?php

namespace Controllers\Role;

use App\Application\Role\DTO\OneRoleCommand;
use App\Application\Role\Handlers\OneRoleCommandHandler;
use App\Application\Role\UseCases\OneRoleUseCase;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetRoleController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly OneRoleUseCase $oneRoleUseCase,
        private readonly OneRoleCommandHandler $oneRoleCommandHandler,
    ) {

    }

    public function __invoke(Request $request)
    {

        try {

            $idEmpresa = $request->input("id");

            $this->isNanId($idEmpresa);

            $command = new OneRoleCommand(
                $idEmpresa
            );

            $result = $this->oneRoleCommandHandler->handle($command);


            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('employee')->warning(
                "La validación fallóal obtener el rol {$request->input('id')}\n" .
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
        } catch (\InvalidArgumentException $e) {
            Log::channel('employee')->warning(
                "Error en algún campo al obtener el rol {$request->input('id')}\n" .
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
            Log::channel('employee')->error(
                "Error al obtener el rol {$request->input('id')}\n" .
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
        if ($id === null || !is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }
    }
}

