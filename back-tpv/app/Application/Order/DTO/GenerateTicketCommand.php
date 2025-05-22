<?php
namespace App\Application\Order\DTO;

use App\Domain\Order\Entities\Order;
use App\Domain\Restaurant\Entities\Restaurant;

class GenerateTicketCommand
{
    public function __construct(
        public readonly Order $order,
        public readonly array $lines,
        public readonly float $total,
        public readonly float $iva,
        public readonly Restaurant $restaurant,
    ) {
    }
}
