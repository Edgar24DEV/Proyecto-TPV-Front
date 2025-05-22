<?php

namespace App\Application\Payment\DTO;

class DeletePaymentCommand
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
