<?php

namespace Controllers\Client;

use App\Application\Client\DTO\UpdateClientCompanyCommand;
use App\Application\Client\Handlers\UpdateClientCompanyCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PutClientCompanyController
{
    public function __construct(
        private readonly UpdateClientCompanyCommandHandler $updateClientCompanyCommandHandler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->validateOrFail($request);

            $command = new UpdateClientCompanyCommand(
                $request->get("id"),
                $request->get("razon_social"),
                $request->get("cif"),
                $request->get("direccion_fiscal"),
                $request->get("correo"),
                $request->get("id_empresa")
            );

            $result = $this->updateClientCompanyCommandHandler->handle($command);

            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('company')->warning(
                "No se pudo actualizar el cliente \n" .
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
                'error' => 'La validación falló',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::channel('company')->warning(
                "No se pudo actualizar el cliente \n" .
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
            ], 400);
        }
    }

    private function validateOrFail(Request $request): void
    {
        $request->validate([
            "razon_social" => "sometimes|string|max:255",
            "cif" => ["sometimes", "string", "regex:/^[ABCDEFGHJKLMNPQRSUVW]\d{7}[0-9A-J]$/i"],
            "direccion_fiscal" => "sometimes|string|max:255",
            "correo" => "sometimes|email|max:255",
            "id_empresa" => "sometimes|integer|exists:empresas,id",
        ]);
    }
}