<?php

namespace App\Application\Category\DTO;

class DeleteCategoryCommand
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
