<?php
// UpdateRestaurantHandler.php
namespace App\Application\Restaurant\Handlers;

use App\Application\Restaurant\DTO\AddRestaurantCommand;
use App\Application\Restaurant\DTO\DeleteRestaurantCommand;
use App\Application\Restaurant\DTO\FindByIdRestaurantCommand;
use App\Application\Restaurant\DTO\LoginRestaurantCommand;
use App\Application\Restaurant\DTO\UpdateRestaurantCommand;
use App\Application\Restaurant\DTO\UpdateRestaurantDTO;
use App\Application\Restaurant\UseCases\DeleteRestaurantUseCase;
use App\Application\Restaurant\UseCases\FindByIdRestaurantUseCase;
use App\Application\Restaurant\UseCases\LoginRestaurantUseCase;
use App\Domain\Restaurant\Entities\Restaurant;
use App\Domain\Audit\Services\AuditService;

class LoginRestaurantCommandHandler
{
    private LoginRestaurantUseCase $loginRestaurantUseCase;
    public function __construct(LoginRestaurantUseCase $loginRestaurantUseCase)
    {
        $this->loginRestaurantUseCase = $loginRestaurantUseCase;
    }

    public function handle(LoginRestaurantCommand $command): Restaurant
    {

        // Ahora, el handler pasa el DTO al caso de uso
        return $this->loginRestaurantUseCase->__invoke($command);
    }

}