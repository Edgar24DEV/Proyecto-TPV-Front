<?php

namespace Controllers\Client;

use App\Application\Client\DTO\DeleteClientCompanyCommand;
use App\Application\Client\Handlers\DeleteClientCompanyCommandHandler;
use App\Application\Client\UseCases\DeleteClientCompanyUseCase;
use App\Application\OrderLine\DTO\DeleteOrderLineCommand;
use App\Application\OrderLine\Handlers\DeleteOrderLineCommandHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Application\OrderLine\UseCases\DeleteOrderLineUseCase;
use App\Infrastructure\Http\Api\Trait\ApiResponseTrait;
use Illuminate\Support\Facades\Log;


class DeleteClientCompanyController
{
    use ApiResponseTrait;


    public function __construct(
        private readonly DeleteClientCompanyUseCase $deleteClientCompanyUseCase,
        private readonly DeleteClientCompanyCommandHandler $deleteClientCompanyCommandHandler,
    ) {

    }


    public function __invoke(Request $request): JsonResponse
    {

        $id = new DeleteClientCompanyCommand(
            $request->input("id"),
        );

        try {
            $this->isNanId($id->getId());
            $success = $this->deleteClientCompanyCommandHandler->handle($id);

            if ($success) {
                return response()->json("Cliente eliminado con éxito");
            }
            Log::channel('company')->warning("No se pudo borrar el cliente\n", [
                '\n     class' => __CLASS__,
                '\n\t line' => __LINE__,
            ]);
            return response()->json([
                'error' => "No se pudo eliminar el cliente"
            ], 500);

        } catch (\InvalidArgumentException $e) {
            Log::channel('company')->warning(
                "No se pudo borrar el cliente \n" .
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
            ], 422);
        } catch (\Exception $e) {
            Log::channel('company')->error(
                "No se pudo borrar el cliente \n" .
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
        if ($id === null || !is_numeric($id) || $id <= 0) {
            throw new \InvalidArgumentException(
                $id <= 0 ? "El ID debe ser positivo" : "El ID debe ser numérico"
            );
        }
    }
}