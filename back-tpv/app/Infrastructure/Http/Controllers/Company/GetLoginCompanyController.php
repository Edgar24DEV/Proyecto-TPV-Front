<?php

namespace Controllers\Company;

use App\Application\Company\DTO\LoginCompanyCommand;
use App\Application\Company\Handlers\LoginCompanyCommandHandler;
use App\Application\Company\UseCases\LoginCompanyUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

class GetLoginCompanyController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly LoginCompanyUseCase $loginCompanyUseCase,
        private readonly LoginCompanyCommandHandler $loginCompanyCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {
        $nombre = $request->input("nombre");
        $contrasenya = $request->input("contrasenya");
        try {

            $command = new LoginCompanyCommand(
                $request->input("nombre"),
                $request->input("contrasenya"),
            );

            $result = $this->loginCompanyCommandHandler->handle($command);
            if ($result->getId() === -1) {
                return response()->json([
                    'error' => 'No se pudo loguear correctamente. Verifica tus credenciales.'
                ], 401);
            }

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('company')->error("Error al loguear la empresa \n" .
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
        }
    }

    private function isNanId($id)
    {
        if ($id === null || !is_numeric($id)) {
            throw new \InvalidArgumentException("El ID no es un número");
        }
    }

}