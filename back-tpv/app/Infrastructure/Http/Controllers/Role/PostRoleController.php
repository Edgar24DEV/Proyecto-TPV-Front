<?php

namespace Controllers\Role;

use App\Application\Role\DTO\AddRoleCommand;
use App\Application\Role\Handlers\AddRoleCommandHandler;
use App\Application\Role\UseCases\AddRoleUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostRoleController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly AddRoleUseCase $addRoleUseCase,
        private readonly AddRoleCommandHandler $addRoleCommandHandler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->validateOrFail($request);

            $command = new AddRoleCommand(
                rol: $request->get("rol"),
                productos: $request->get("productos"),
                categorias: $request->get("categorias"),
                tpv: $request->get("tpv"),
                usuarios: $request->get("usuarios"),
                mesas: $request->get("mesas"),
                restaurante: $request->get("restaurante"),
                clientes: $request->get("clientes"),
                empresa: $request->get("empresa"),
                pago: $request->get("pago"),
                idEmpresa: $request->get("id_empresa"),
            );

            $result = $this->addRoleCommandHandler->handle($command);

            $this->validateOfUseCase($result);

            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('employee')->warning(
                "La validación falló al crear el rol {$request->input('rol')}\n" .
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
                "Error en algún campo al crear el rol {$request->input('rol')}\n" .
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
                "Error al crear el rol {$request->input('rol')}\n" .
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
        $request->validate([
            "rol" => "required|string|max:100",
            "productos" => "required|boolean", //activo" => "required|in:0,1",
            "categorias" => "required|boolean",
            "tpv" => "required|boolean",
            "usuarios" => "required|boolean",
            "mesas" => "required|boolean",
            "restaurante" => "required|boolean",
            "clientes" => "required|boolean",
            "empresa" => "required|boolean",
            "pago" => "required|boolean",
            "id_empresa" => "required|integer|exists:empresas,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        if ($result->getId() < 1) {
            $this->apiError("No se ha podido crear el rol");
        }
    }
}
