<?php

namespace App\Domain\Employee\Repositories;
use App\Application\Employee\DTO\AddEmployeeCommand;
use App\Application\Restaurant\DTO\AddRestaurantCommand;
use App\Application\Restaurant\DTO\AddRestaurantProductCommand;
use App\Application\Restaurant\DTO\LoginRestaurantCommand;
use App\Application\Restaurant\DTO\UpdateRestaurantCommand;
use App\Domain\Employee\Entities\Employee;
use App\Domain\Restaurant\Entities\Restaurant;

interface RestaurantRepositoryInterface
{
    public function login(LoginRestaurantCommand $command): Restaurant;
    public function existwithCompany(int $idRestaurant, int $idComany): bool;

    public function saveRestaurantProduct(AddRestaurantProductCommand $command): AddRestaurantProductCommand;
    public function create(AddRestaurantCommand $command): Restaurant;
    public function update(UpdateRestaurantCommand $command): Restaurant;
    public function findByCompanyId(int $companyId): array;
    public function softDelete(int $id): bool;

}
