<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Api\Dto;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiErrorResponse
{
    private $response;

    public function __construct(?string $message = null, $data = [], ?int $status = 400)
    {
        $mainStatus = substr((string) $status, 0, 1);
        $stringStatus = in_array($mainStatus, [4, 5]) ? 'error' : 'success';

        $response = new JsonResponse([
            'status' => $stringStatus,
            'data' => $data,
            'message' => $message,
        ], $status);

        $this->response = $response;
    }

    public function __invoke()
    {
        return $this->response;
    }
}