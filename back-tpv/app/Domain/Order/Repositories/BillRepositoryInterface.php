<?php

namespace App\Domain\Order\Repositories;

use App\Domain\Order\Entities\Order;

interface BillRepositoryInterface
{
    // El método 'generate' debe estar presente en el repositorio


    // Método para obtener el pedido con las líneas
    public function getOrderWithLines(int $idPedido, int $idRestaurante, int $idCliente, float $total, string $tipo): array;
}

