<?php

namespace App\Application\Product\DTO;

class DeleteProductCommand
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
