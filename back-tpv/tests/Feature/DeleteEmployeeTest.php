<?php

namespace Tests\Feature;

use App\Application\Employee\Handlers\DeleteEmployeeCommandHandler;
use App\Application\Employee\UseCases\DeleteEmployeeUseCase;
use Controllers\Employee\DeleteEmployeesController;
use Illuminate\Http\Request;
use Tests\TestCase;

class DeleteEmployeeTest extends TestCase
{
    public function test_successful_employee_deletion()
    {
        // Mock del caso de uso
        $useCaseMock = $this->createMock(DeleteEmployeeUseCase::class);
        $useCaseMock->method('__invoke')
            ->willReturn(true);

        // Mock para el handler
        $commandHandlerMock = $this->createMock(DeleteEmployeeCommandHandler::class);
        $commandHandlerMock->method('handle')->willReturn(true);

        $controller = new DeleteEmployeesController($useCaseMock, $commandHandlerMock);
        $request = new Request(['id' => 1]);

        $response = $controller->__invoke($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Empleado eliminado con éxito', $response->getData());
    }

    public function test_invalid_id_validation()
    {
        $useCaseMock = $this->createMock(DeleteEmployeeUseCase::class);
        $commandHandlerMock = $this->createMock(DeleteEmployeeCommandHandler::class);

        $controller = new DeleteEmployeesController($useCaseMock, $commandHandlerMock);

        // ID no numérico
        $request = new Request(['id' => '-5']);
        $response = $controller->__invoke($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('error', (array) $response->getData());
    }
}