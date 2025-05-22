<?php

namespace App\Domain\Employee\Repositories;

interface RestaurantRepositoryInterface
{
    public function exist(int $idRestaurant): bool;
}
