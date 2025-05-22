// enter-pin-modal.component.spec.ts

import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { EnterPinModalComponent } from './enter-pin-modal.component';
import { ModalController } from '@ionic/angular';
import { Router } from '@angular/router';
import { of, throwError } from 'rxjs';
import { EmployeeService } from 'src/app/services/employee.service';
import { RoleService } from 'src/app/services/role.service';
import { Employee } from 'src/app/models/Employee';
import { Role } from 'src/app/models/Role';

describe('EnterPinModalComponent', () => {
  let component: EnterPinModalComponent;
  let fixture: ComponentFixture<EnterPinModalComponent>;
  let employeeServiceSpy: jasmine.SpyObj<EmployeeService>;
  let roleServiceSpy: jasmine.SpyObj<RoleService>;
  let modalControllerSpy: jasmine.SpyObj<ModalController>;
  let routerSpy: jasmine.SpyObj<Router>;

  beforeEach(waitForAsync(() => {
    employeeServiceSpy = jasmine.createSpyObj('EmployeeService', [
      'postLoginEmployee',
    ]);
    roleServiceSpy = jasmine.createSpyObj('RoleService', ['getRole']);
    modalControllerSpy = jasmine.createSpyObj('ModalController', ['dismiss']);
    routerSpy = jasmine.createSpyObj('Router', ['navigate']);

    TestBed.configureTestingModule({
      imports: [EnterPinModalComponent],
      providers: [
        { provide: EmployeeService, useValue: employeeServiceSpy },
        { provide: RoleService, useValue: roleServiceSpy },
        { provide: ModalController, useValue: modalControllerSpy },
        { provide: Router, useValue: routerSpy },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(EnterPinModalComponent);
    component = fixture.componentInstance;
    component.idEmployee = 1;
    fixture.detectChanges();
  }));

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  it('should append digit and mask it', () => {
    component.appendDigit('1');
    expect(component.pin).toBe('1');
    expect(component.pinLength).toBe('*');
  });

  it('should delete last digit', () => {
    component.pin = '12';
    component.pinLength = '**';
    component.deleteDigit();
    expect(component.pin).toBe('1');
    expect(component.pinLength).toBe('*');
  });

  it('should submit pin and navigate on success', () => {
    const employeeMock: Employee = {
      id: 1,
      nombre: 'Juan',
      idRol: 5,
      pin: 1234,
      idEmpresa: 7,
    } as Employee;

    const roleMock = new Role({
      id: 5,
      rol: 'Admin',
      productos: true,
      categorias: true,
      tpv: true,
      usuarios: true,
      mesas: true,
      restaurante: true,
      clientes: true,
      empresa: true,
      pago: true,
    });

    component.pin = '1234';
    employeeServiceSpy.postLoginEmployee.and.returnValue(of(employeeMock));
    roleServiceSpy.getRole.and.returnValue(of(roleMock));

    component.submitPin();

    expect(employeeServiceSpy.postLoginEmployee).toHaveBeenCalledWith(1, 1234);
    expect(roleServiceSpy.getRole).toHaveBeenCalledWith(5);
    expect(modalControllerSpy.dismiss).toHaveBeenCalled();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['/employees/panel']);
    expect(localStorage.getItem('employeeRol')).toContain(
      '{"_id":5,"_rol":"Admin","_productos":true,"_categorias":true,"_tpv":true,"_usuarios":true,"_mesas":true,"_restaurante":true,"_clientes":true,"_empresa":true,"_pago":true,"_idEmpresa":7,"_idEmpleado":1,"_nombre":"Juan"}'
    );
  });

  it('should handle login error', () => {
    component.pin = '1234';
    employeeServiceSpy.postLoginEmployee.and.returnValue(
      throwError(() => new Error('Login failed'))
    );
    component.submitPin();
    expect(component.error).toBeTrue();
    expect(component.pin).toBe('');
    expect(component.pinLength).toBe('');
  });

  it('should cancel and dismiss modal', () => {
    component.cancel();
    expect(modalControllerSpy.dismiss).toHaveBeenCalled();
  });
});
