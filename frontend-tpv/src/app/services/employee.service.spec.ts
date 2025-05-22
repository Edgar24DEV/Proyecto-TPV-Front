import { TestBed } from '@angular/core/testing';
import { EmployeeService } from './employee.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Employee } from '../models/Employee';
import { Restaurant } from '../models/Restaurant';
import { environment } from 'src/environments/environment';

describe('EmployeeService', () => {
  let service: EmployeeService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        EmployeeService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(EmployeeService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  describe('getListEmployeeRestaurant', () => {
    it('should get employees by restaurant id', () => {
      const mockEmployees = [
        { id: 1, nombre: 'John Doe', pin: '1234', idRol: 2, idEmpresa: 1 },
        { id: 2, nombre: 'Jane Doe', pin: '5678', idRol: 3, idEmpresa: 1 },
      ];

      service.getListEmployeeRestaurant(5).subscribe(employees => {
        expect(employees.length).toBe(2);
        expect(employees[0]).toBeInstanceOf(Employee);
        expect(employees[0].nombre).toBe('John Doe');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employees?id_restaurante=5`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockEmployees });
    });

    it('should throw error when response is not an array', () => {
      service.getListEmployeeRestaurant(5).subscribe({
        error: (err) => {
          expect(err.message).toBe('La respuesta del servidor no es una lista');
        }
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employees?id_restaurante=5`);
      req.flush({ data: {} }); // Non-array response
    });
  });

  describe('postLoginEmployee', () => {
    it('should authenticate employee', () => {
      const mockEmployee = { id: 1, nombre: 'John Doe', pin: '1234', idRol: 2, idEmpresa: 1 };

      service.postLoginEmployee(1, 1234).subscribe(employee => {
        expect(employee).toBeInstanceOf(Employee);
        expect(employee.nombre).toBe('John Doe');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employees/login`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({ id: '1', pin: '1234' });

      req.flush({ data: mockEmployee });
    });
  });

  describe('postEmployee', () => {
    it('should create a new employee', () => {
      const newEmployee = new Employee({ nombre: 'New Employee', pin: 0, idRol: 2, idEmpresa: 1 });
      const mockResponse = new Employee({ id: 3, nombre: 'New Employee', pin: 0, idRol: 2, idEmpresa: 1 });

      service.postEmployee(newEmployee, 5).subscribe(employee => {
        expect(employee).toBeInstanceOf(Employee);
        expect(employee.nombre).toBe('New Employee');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employees`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({
        nombre: 'New Employee',
        pin: 0,
        id_empresa: 1,
        id_rol: 2,
        id_restaurante: 5
      });
      req.flush(mockResponse);
    });
  });

  describe('putEmployee', () => {
    it('should update an employee', () => {
      const updatedEmployee = new Employee({ id: 1, nombre: 'Updated Name', pin: 9999, idRol: 2, idEmpresa: 1 });
      const mockResponse = new Employee({ id: 1, nombre: 'Updated Name', pin: 9999, idRol: 2, idEmpresa: 1 });

      service.putEmployee(updatedEmployee).subscribe(employee => {
        expect(employee).toBeInstanceOf(Employee);
        expect(employee.nombre).toBe('Updated Name');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employees`);
      expect(req.request.method).toBe('PUT');
      expect(req.request.body).toEqual({
        id: 1,
        nombre: 'Updated Name',
        pin: 9999,
        id_empresa: 1,
        id_rol: 2
      });
      req.flush(mockResponse);
    });
  });

  describe('getEmployee', () => {
    it('should get employee by id', () => {
      const mockEmployee = { id: 1, nombre: 'John Doe', pin: '1234', idRol: 2, idEmpresa: 1 };

      service.getEmployee(1).subscribe(employee => {
        expect(employee).toBeInstanceOf(Employee);
        expect(employee.id).toBe(1);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employee?id=1`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockEmployee });
    });
  });

  describe('deleteEmployee', () => {
    it('should delete an employee', () => {
      service.deleteEmployee(1).subscribe(response => {
        expect(response).toBeTruthy();
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employees?id=1`);
      expect(req.request.method).toBe('DELETE');
      req.flush({ success: true });
    });
  });

  describe('getListEmployeeCompany', () => {
    it('should get employees by company id', () => {
      const mockEmployees = [
        { id: 1, nombre: 'Alice', pin: 1234, idRol: 2, idEmpresa: 1 },
        { id: 2, nombre: 'Bob', pin: 5678, idRol: 3, idEmpresa: 1 },
      ];

      service.getListEmployeeCompany(1).subscribe(employees => {
        expect(employees.length).toBe(2);
        expect(employees[0]).toBeInstanceOf(Employee);
        expect(employees[1].nombre).toBe('Bob');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employees-company?id_empresa=1`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockEmployees });
    });
  });

  describe('deleteEmployeeRestaurant', () => {
    it('should delete employee restaurant relation', () => {
      service.deleteEmployeeRestaurant(1, 5).subscribe(response => {
        expect(response).toBeTruthy();
      });

      const req = httpMock.expectOne(`${mockApiUrl}/employee-restaurant?id_empleado=1&id_restaurante=5`);
      expect(req.request.method).toBe('DELETE');
      req.flush({ success: true });
    });
  });
});
