import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ListEmployeeRestaurantComponent } from './list-employee-restaurant.component';
import { EmployeeService } from 'src/app/services/employee.service';
import { Router } from '@angular/router';
import { AlertController, IonicModule, ModalController } from '@ionic/angular';
import { CommonModule } from '@angular/common';
import { AppComponent } from 'src/app/app.component';
import { of, throwError } from 'rxjs';
import { Employee } from 'src/app/models/Employee';
import { EnterPinModalComponent } from '../enter-pin-modal/enter-pin-modal.component';

describe('ListEmployeeRestaurantComponent', () => {
  let component: ListEmployeeRestaurantComponent;
  let fixture: ComponentFixture<ListEmployeeRestaurantComponent>;
  let mockEmployeeService: jasmine.SpyObj<EmployeeService>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockAlertController: jasmine.SpyObj<AlertController>;
  let mockModalController: jasmine.SpyObj<ModalController>;
  let mockAppComponent: jasmine.SpyObj<AppComponent>;

  beforeEach(async () => {
    mockEmployeeService = jasmine.createSpyObj('EmployeeService', [
      'getListEmployeeRestaurant',
    ]);
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockAlertController = jasmine.createSpyObj('AlertController', ['create']);
    mockModalController = jasmine.createSpyObj('ModalController', ['create']);
    mockAppComponent = jasmine.createSpyObj('AppComponent', [], {
      restaurantName: 'Test Restaurant',
      employeeRol: { categorias: true },
    });

    await TestBed.configureTestingModule({
      imports: [
        IonicModule.forRoot(),
        CommonModule,
        ListEmployeeRestaurantComponent,
      ], // Se importa el componente standalone
      providers: [
        { provide: EmployeeService, useValue: mockEmployeeService },
        { provide: Router, useValue: mockRouter },
        { provide: AlertController, useValue: mockAlertController },
        { provide: ModalController, useValue: mockModalController },
        { provide: AppComponent, useValue: mockAppComponent },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(ListEmployeeRestaurantComponent);
    component = fixture.componentInstance;
  });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  describe('ngOnInit', () => {


    it('should initialize restaurant and employeeRol from AppComponent', () => {
      spyOn(localStorage, 'getItem').and.returnValue('1');
      mockEmployeeService.getListEmployeeRestaurant.and.returnValue(
        of([{ id: 1, nombre: 'John Doe' }] as Employee[])
      );

      component.ngOnInit();

      expect(component.idRestaurant).toBe(1);
      expect(component.restaurantName).toBe('Test Restaurant');
      expect(component.employeeRol?.categorias).toBe(true);
      expect(component.listEmployees.length).toBe(1);
    });
  });

  describe('listOfEmployees', () => {
    it('should fetch employees successfully', () => {
      mockEmployeeService.getListEmployeeRestaurant.and.returnValue(
        of([{ id: 1, nombre: 'Alice' }] as Employee[])
      );

      component.listOfEmployees();

      expect(component.listEmployees.length).toBe(1);
      expect(component.listEmployees[0].nombre).toBe('Alice');
    });

    it('should navigate to login if employee fetch fails', () => {
      mockEmployeeService.getListEmployeeRestaurant.and.returnValue(
        throwError(() => new Error('API Error'))
      );

      component.listOfEmployees();

      expect(mockRouter.navigate).toHaveBeenCalledWith(['/loginRestaurant']);
    });
  });

 describe('presentAlert', () => {
    it('should create an alert for entering PIN', async () => {
      const alertSpy = jasmine.createSpyObj('IonAlert', ['present']);
      alertSpy.present.and.returnValue(Promise.resolve());

      mockAlertController.create.and.returnValue(Promise.resolve(alertSpy));

      await component.presentAlert({
        id: 1,
        nombre: 'Test Employee',
        pin: 1234,
        idRol: 2,
        idEmpresa: 3,
      });

      expect(mockAlertController.create).toHaveBeenCalledWith(
        jasmine.objectContaining({ header: 'Introduce el PIN' })
      );
      expect(alertSpy.present).toHaveBeenCalled();
    });
  });
/*
  describe('presentPinModal', () => {
    it('should open PIN modal for an employee', async () => {
      const modalSpy = jasmine.createSpyObj('modal', ['present', 'onDidDismiss']);
      modalSpy.present.and.returnValue(Promise.resolve());
      modalSpy.onDidDismiss.and.returnValue(Promise.resolve({ data: '1234' }));

      mockModalController.create.and.returnValue(Promise.resolve(modalSpy));

     await component.presentPinModal({ id: 1, nombre: 'Test Employee', pin: 1234, idRol: 2, idEmpresa: 3 });


      expect(mockModalController.create).toHaveBeenCalledWith(
        jasmine.objectContaining({
          component: EnterPinModalComponent,
          componentProps: { idEmployee: 1 },
        })
      );
      expect(modalSpy.present).toHaveBeenCalled();
    });

  });*/
});
