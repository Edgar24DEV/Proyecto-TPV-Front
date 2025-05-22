<?php


namespace Controllers\Client;

use App\Application\Client\DTO\AddClientCompanyCommand;
use App\Application\Client\Handlers\AddClientCompanyCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class PostClientCompanyController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly AddClientCompanyCommandHandler $addClientCompanyCommandHandler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            // Validamos los datos de la solicitud
            $this->validateOrFail($request);

            // Creamos el comando con los datos de la solicitud
            $command = new AddClientCompanyCommand(
                $request->get("razon_social"),
                $request->get("cif"),
                $request->get("direccion_fiscal"),
                $request->get("correo"),
                $request->get("id_empresa")
            );

            // Ejecutamos el handler para manejar el comando
            $result = $this->addClientCompanyCommandHandler->handle($command);

            // Validamos el resultado del caso de uso
            $this->validateOfUseCase($result);

            // Devolvemos la respuesta en formato JSON
            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('company')->error(
                "No se pudo crear el cliente \n" .
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
            // Capturamos errores de validación
            return response()->json([
                'error' => 'La validación falló.',
                'messages' => $e->errors()
            ], 422);
        }


    }

    private function validateOrFail($request): void
    {
        // Validamos los datos del cliente
        $request->validate([
            "razon_social" => "required|string|max:255",
            "cif" => ["required", "string", "regex:/^[ABCDEFGHJKLMNPQRSUVW]\d{7}[0-9A-J]$/i"],
            "direccion_fiscal" => "required|string|max:255",
            "correo" => "required|email|max:255",
            "id_empresa" => "required|integer|exists:empresas,id",
        ]);
    }

    private function validateOfUseCase($result): void
    {
        // Verificamos que el ID del cliente sea válido
        if ($result->id < 1) {
            $this->apiError("No se ha podido crear el cliente");
        }
    }
}