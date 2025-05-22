<?php

namespace App\Domain\Order\Services;

use App\Domain\Company\Entities\Client;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\Payment;
use App\Domain\Restaurant\Entities\Restaurant;

interface BillGeneratorServiceInterface
{
    public function generate(Order $order, array $lines, float $total, float $iva, Restaurant $restaurant, Client $client, Payment $payment): string;
}
