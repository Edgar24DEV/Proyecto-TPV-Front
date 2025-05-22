import { ComponentFixture, fakeAsync, flush, TestBed, tick } from '@angular/core/testing';
import { UsersAdminComponent } from './users-admin.component';
import { EmployeeService } from 'src/app/services/employee.service';
import { RoleService } from 'src/app/services/role.service';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { AlertService } from 'src/app/services/alert.service';
import { Router } from '@angular/router';
import { AlertController, IonicModule } from '@ionic/angular';
import { FormBuilder, ReactiveFormsModule } from '@angular/forms';
import { of, throwError } from 'rxjs';
import { Employee } from 'src/app/models/Employee';
import { Role } from 'src/app/models/Role';
import { Restaurant } from 'src/app/models/Restaurant';
import { EmployeeRol } from 'src/app/models/Employee-rol';

describe('UsersAdminComponent', () => {
  let component: UsersAdminComponent;
  let fixture: ComponentFixture<UsersAdminComponent>;
  let mockEmployeeService: jasmine.SpyObj<EmployeeService>;
  let mockRoleService: jasmine.SpyObj<RoleService>;
  let mockRestaurantService: jasmine.SpyObj<RestaurantService>;
  let mockAlertService: jasmine.SpyObj<AlertService>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockAlertController: jasmine.SpyObj<AlertController>;
  let mockFormBuilder: FormBuilder;

  beforeEach(async () => {
    mockEmployeeService = jasmine.createSpyObj('EmployeeService', [
      'getListEmployeeCompany',
      'getListEmployeeRestaurant',
      'postEmployee',
      'postEmployeeRestaurant',
      'postEmployeeRestaurantRelation',
      'putEmployee',
      'deleteEmployee',
    ]);
    mockRoleService = jasmine.createSpyObj('RoleService', ['getRolesCompany']);
    mockRestaurantService = jasmine.createSpyObj('RestaurantService', [
      'getListRestaurantCompany',
      'getListEmployeeRestaurants',
    ]);
    mockAlertService = jasmine.createSpyObj('AlertService', ['show']);
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockAlertController = jasmine.createSpyObj('AlertController', ['create']);
    mockFormBuilder = new FormBuilder();

    await TestBed.configureTestingModule({
      imports: [
        IonicModule.forRoot(),
        ReactiveFormsModule,
        UsersAdminComponent,
      ],
      providers: [
        { provide: EmployeeService, useValue: mockEmployeeService },
        { provide: RoleService, useValue: mockRoleService },
        { provide: RestaurantService, useValue: mockRestaurantService },
        { provide: AlertService, useValue: mockAlertService },
        { provide: Router, useValue: mockRouter },
        { provide: AlertController, useValue: mockAlertController },
        { provide: FormBuilder, useValue: mockFormBuilder },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(UsersAdminComponent);
    component = fixture.componentInstance;
    mockEmployeeService.getListEmployeeCompany.and.returnValue(
      of([{ id: 1, nombre: 'Juan' }] as Employee[])
    );
    mockEmployeeService.getListEmployeeRestaurant.and.returnValue(
      of([{ id: 2, nombre: 'Maria' }] as Employee[])
    );
    mockRestaurantService.getListRestaurantCompany.and.returnValue(of([]));
    mockRoleService.getRolesCompany.and.returnValue(of([]));
    component.employeeForm = mockFormBuilder.group({
      nombre: [''],
      rol: [''],
      pin: [''],
    });
  });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  describe('ngOnInit with different localStorage scenarios', () => {
    const mockEmployeeRol = {
      idEmpresa: 99,
      usuarios: true,
    };

    function mockLocalStorageValues(
      values: Partial<Record<string, string | null>>
    ) {
      spyOn(localStorage, 'getItem').and.callFake((key: string) => {
        return values[key] ?? null;
      });
    }

    it('should initialize correctly with idCompany and employeeRol', () => {
      mockLocalStorageValues({
        idCompany: '1',
        employeeRol: JSON.stringify(mockEmployeeRol),
      });

      component.ngOnInit();

      expect(component.idCompany).toBe(1);
      expect(component.employeeRol.idEmpresa).toBe(99);
      expect(mockRouter.navigate).not.toHaveBeenCalled();
    });

    it('should initialize correctly with idRestaurant and employeeRol', () => {
      mockLocalStorageValues({
        idRestaurant: '2',
        employeeRol: JSON.stringify(mockEmployeeRol),
      });

      component.ngOnInit();

      expect(component.idRestaurant).toBe(2);
      expect(component.employeeRol.idEmpresa).toBe(99);
      expect(mockRouter.navigate).not.toHaveBeenCalled();
    });

    it('should navigate to employees/panel if employeeRol has no usuarios', () => {
      const badRol = { idEmpresa: 99, usuarios: false };

      mockLocalStorageValues({
        idRestaurant: '2',
        employeeRol: JSON.stringify(badRol),
      });

      component.ngOnInit();

      expect(mockRouter.navigate).toHaveBeenCalledWith(['employees/panel']);
    });

    it('should navigate to root if idRestaurant is missing', () => {
      mockLocalStorageValues({
        employeeRol: JSON.stringify(mockEmployeeRol),
      });

      component.ngOnInit();

      expect(mockRouter.navigate).toHaveBeenCalledWith(['']);
    });
  });

  describe('listOfEmployees', () => {
    it('should load employees from the company if idRestaurant is not provided', () => {
      component.idCompany = 1;
      mockEmployeeService.getListEmployeeCompany.and.returnValue(
        of([{ id: 1, nombre: 'Juan' }] as Employee[])
      );

      component.listOfEmployees();

      expect(mockEmployeeService.getListEmployeeCompany).toHaveBeenCalledWith(
        1
      );
      expect(component.listEmployees.length).toBe(1);
      expect(component.listEmployees[0].nombre).toBe('Juan');
    });

    it('should load employees from the restaurant if idRestaurant is provided', () => {
      component.idRestaurant = 2;
      mockEmployeeService.getListEmployeeRestaurant.and.returnValue(
        of([{ id: 2, nombre: 'Maria' }] as Employee[])
      );

      component.listOfEmployees();

      expect(
        mockEmployeeService.getListEmployeeRestaurant
      ).toHaveBeenCalledWith(2);
      expect(component.listEmployees.length).toBe(1);
      expect(component.listEmployees[0].nombre).toBe('Maria');
    });

    it('should show alert if there is an error loading employees', () => {
      mockEmployeeService.getListEmployeeCompany.and.returnValue(
        throwError(() => new Error('Error'))
      );

      component.listOfEmployees();

      expect(mockAlertService.show).toHaveBeenCalledWith(
        'Error',
        '❌ Error al cargar empleados',
        'error'
      );
    });
  });

  describe('saveEmployee', () => {
    it('should update an existing employee', () => {
      component.employee = {
        id: 1,
        nombre: 'Carlos',
        pin: 1234,
        idRol: 1,
        idEmpresa: 1,
      };
      component.idCompany = 1;
      component.idRestaurant = 1;
      component.employeeForm.patchValue({
        nombre: 'Carlos',
        rol: 1,
        pin: '5678',
      });
      mockEmployeeService.putEmployee.and.returnValue(
        of({
          id: 1,
          nombre: 'Juan Pérez',
          pin: 1234,
          idRol: 2,
          idEmpresa: 99,
        })
      );

      component.saveEmployee();

      expect(mockEmployeeService.putEmployee).toHaveBeenCalled();
    });
  });
});
