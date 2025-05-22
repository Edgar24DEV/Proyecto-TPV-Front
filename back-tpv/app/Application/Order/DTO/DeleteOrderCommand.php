<?php

namespace App\Application\Order\DTO;

class DeleteOrderCommand
{
    public function __construct(
        private readonly ?int $id,
    ) {
    }
    //Getter
    public function getId(): ?int
    {
        return $this->id;
    }

}
