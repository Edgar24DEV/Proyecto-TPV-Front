<?php

namespace App\Application\Table\DTO;

class DeleteTableCommand
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
