<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Api\Dto;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiSuccessResponse
{
    private $response;

    public function __construct($data = [], ?int $status = 200, $extraHeaders = [], ?string $message = null)
    {
        $mainStatus = substr((string) $status, 0, 1);
        $stringStatus = in_array($mainStatus, [4, 5]) ? 'error' : 'success';

        $response = new JsonResponse([
            'status' => $stringStatus,
            'data' => $data,
            'message' => $message,
        ], $status);

        $response->headers->add($extraHeaders);
        $this->response = $response;
    }

    public function __invoke()
    {
        return $this->response;
    }
}