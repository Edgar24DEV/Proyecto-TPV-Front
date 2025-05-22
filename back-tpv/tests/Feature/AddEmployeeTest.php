<?php

namespace Tests\Feature;

use App\Application\Employee\Handlers\AddEmployeeCommandHandler;
use App\Application\Employee\UseCases\AddEmployeeUseCase;
use App\Domain\Employee\Entities\Employee;
use Controllers\Employee\PostEmployeesController;
use Illuminate\Http\Request;
use Tests\TestCase;

class AddEmployeeTest extends TestCase
{
    public function test_successful_employee_addition()
    {
        // Crear un empleado simulado
        $fakeEmployee = new Employee(
            id: 1,
            nombre: 'Juan Pérez',
            pin: 1234,
            idRol: 1,
            idEmpresa: 1,
        );

        // Mock del caso de uso
        $useCaseMock = $this->createMock(AddEmployeeUseCase::class);
        $useCaseMock->method('__invoke')
            ->willReturn($fakeEmployee);

        // Mock para el handler
        $commandHandlerMock = $this->createMock(AddEmployeeCommandHandler::class);
        $commandHandlerMock->method('handle')->willReturn($fakeEmployee);

        $controller = new PostEmployeesController($useCaseMock, $commandHandlerMock);

        $request = new Request([
            'nombre' => 'Juan Pérez',
            'pin' => '1234',
            'id_empresa' => 1,
            'id_rol' => 1,
            'id_restaurante' => 1,
        ]);

        $response = $controller->__invoke($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            'id' => 1,
            'nombre' => 'Juan Pérez',
            'pin' => 1234,
            'idRol' => 1,
            'idEmpresa' => 1,
        ], (array) $response->getData());
    }

    public function test_validation_failure_when_missing_fields()
    {
        $useCaseMock = $this->createMock(AddEmployeeUseCase::class);
        $commandHandlerMock = $this->createMock(AddEmployeeCommandHandler::class);
        $controller = new PostEmployeesController($useCaseMock, $commandHandlerMock);

        $request = new Request([]); // Campos vacíos
        $response = $controller->__invoke($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('error', (array) $response->getData());
        $this->assertArrayHasKey('messages', (array) $response->getData());
    }
}
