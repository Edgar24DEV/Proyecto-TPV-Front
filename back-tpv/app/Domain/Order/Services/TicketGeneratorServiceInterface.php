<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Entities\Order;
use App\Domain\Restaurant\Entities\Restaurant;

interface TicketGeneratorServiceInterface
{
    public function generate(Order $order, array $lines, float $total, float $iva, Restaurant $restaurant): string;
}
