<?php

namespace Tests\Unit\Application\Role\UseCases;

use App\Application\Role\DTO\DeleteRoleCommand;
use App\Application\Role\UseCases\DeleteRoleUseCase;
use App\Infrastructure\Repositories\EloquentRoleRepository;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class DeleteRoleUseCaseTest extends TestCase
{
    public function test_delete_role_successfully()
    {
        // Arrange
        $command = new DeleteRoleCommand(1);

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $logMock = $this->createMock(\Illuminate\Log\Logger::class);
        Log::shouldReceive('channel')->with('restaurant')->andReturn($logMock);

        // Expectations
        $roleRepo->method('exist')
            ->with(1)
            ->willReturn(true);

        $roleRepo->method('delete')
            ->with(1)
            ->willReturn(true);

        // Caso de uso
        $useCase = new DeleteRoleUseCase($roleRepo);

        // Act
        $result = $useCase($command);

        // Assert
        $this->assertTrue($result);
        $logMock->expects($this->never())->method('error');
    }

    public function test_delete_role_fails_when_role_does_not_exist()
    {
        // Arrange
        $command = new DeleteRoleCommand(0);

        // Mocks
        $roleRepo = $this->createMock(EloquentRoleRepository::class);
        $logMock = $this->createMock(\Illuminate\Log\Logger::class);
        Log::shouldReceive('channel')->with('restaurant')->andReturn($logMock);

        // Expectations
        $roleRepo->method('exist')
            ->with(0)
            ->willReturn(false);

        // Caso de uso
        $useCase = new DeleteRoleUseCase($roleRepo);

        // Act
        try {
            $useCase($command);
            $this->fail('Se esperaba una excepción');
        } catch (\Exception $e) {
            // Assert
            $this->assertEquals("ID rol inválido", $e->getMessage());
            $logMock->expects($this->never())->method('error');
        }
    }
}
