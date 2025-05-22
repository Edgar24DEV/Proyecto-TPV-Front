<?php
namespace App\Application\Order\DTO;

use App\Domain\Company\Entities\Client;
use App\Domain\Order\Entities\Order;
use App\Domain\Order\Entities\Payment;
use App\Domain\Restaurant\Entities\Restaurant;

class GenerateBillCommand
{
    public function __construct(
        public readonly Order $order,
        public readonly array $lines,
        public readonly float $total,
        public readonly float $iva,
        public readonly Restaurant $restaurant,
        public readonly Client $client,
        public readonly Payment $payment
    ) {
    }
}
