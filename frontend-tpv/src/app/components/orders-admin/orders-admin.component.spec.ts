import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { OrdersAdminComponent } from './orders-admin.component';
import { IonicModule } from '@ionic/angular';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { RouterTestingModule } from '@angular/router/testing';
import { ReactiveFormsModule } from '@angular/forms';
import { of } from 'rxjs';
import { Order } from 'src/app/models/Order';
import { OrderService } from 'src/app/services/order.service';
import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { Router } from '@angular/router';
import { EmployeeRol } from 'src/app/models/Employee-rol';

describe('OrdersAdminComponent', () => {
  let component: OrdersAdminComponent;
  let fixture: ComponentFixture<OrdersAdminComponent>;
  let mockOrderService: jasmine.SpyObj<OrderService>;
  let router: Router;
  let localStorageGetItemSpy: jasmine.Spy;

  beforeEach(waitForAsync(() => {
    // Crear mock para OrderService
    mockOrderService = jasmine.createSpyObj('OrderService', [
      'getOrdersByRestaurant', 
      'getOrdersByCompany',
      'getOrderTable',
      'postOrder',
      'putOrderDiners',
      'putOrderStatus'
    ]);

    TestBed.configureTestingModule({
      imports: [
        OrdersAdminComponent, // Componente standalone
        IonicModule.forRoot(),
        HttpClientTestingModule,
        RouterTestingModule.withRoutes([
          { path: 'employees', component: class {} },
          { path: 'employees/panel', component: class {} },
          { path: '', component: class {} },
          { path: 'payment', component: class {} }
        ]),
        ReactiveFormsModule
      ],
      providers: [
        { provide: OrderService, useValue: mockOrderService }
      ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA]
    }).compileComponents();

    fixture = TestBed.createComponent(OrdersAdminComponent);
    component = fixture.componentInstance;
    router = TestBed.inject(Router);
    
    // Configurar spies
    spyOn(router, 'navigate');
    localStorageGetItemSpy = spyOn(localStorage, 'getItem');

    // Configurar valores por defecto para localStorage
    mockLocalStorage({
      'idRestaurant': '1',
      'idCompany': '1',
      'employeeRol': JSON.stringify({
        idEmpresa: 1,
        pago: true,
        // Otras propiedades necesarias de EmployeeRol
      })
    });

    // Configurar respuestas mock para los servicios
    mockOrderService.getOrdersByRestaurant.and.returnValue(of([]));
    mockOrderService.getOrdersByCompany.and.returnValue(of([]));
    
    fixture.detectChanges();
  }));

  afterEach(() => {
    // Limpiar spies después de cada test
    localStorageGetItemSpy.calls.reset();
  });

  // Función helper para mockear localStorage
  function mockLocalStorage(data: {[key: string]: string}) {
    localStorageGetItemSpy.and.callFake((key: string) => data[key] || null);
  }

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should initialize with empty filterOrder', () => {
    expect(component.filterOrder).toEqual([]);
  });

  it('should load orders on init when restaurant id exists', () => {
    const testOrders: Order[] = [
      new Order({
        id: 1,
        estado: 'Abierto',
        idMesa: 1,
        comensales: 2,
        fechaInicio: new Date(),
        fechaFin: new Date()
      })
    ];
    
    mockOrderService.getOrdersByRestaurant.and.returnValue(of(testOrders));
    mockOrderService.getOrdersByCompany.and.returnValue(of(testOrders));

    component.ngOnInit();
    
    expect(mockOrderService.getOrdersByRestaurant).toHaveBeenCalledWith(1);
    expect(mockOrderService.getOrdersByCompany).toHaveBeenCalledWith(1);
  });

  it('should navigate to employees panel if payment is not authorized', () => {
    // Configurar mock específico para este test
    mockLocalStorage({
      'idRestaurant': '1',
      'idCompany': '1',
      'employeeRol': JSON.stringify({ pago: false })
    });

    component.ngOnInit();
    expect(router.navigate).toHaveBeenCalledWith(['employees/panel']);
  });

  it('should filter by status', () => {
    component.filterOrder = [
      new Order({ estado: 'Cerrado', fechaInicio: new Date('2023-01-02') }),
      new Order({ estado: 'Abierto', fechaInicio: new Date('2023-01-01') })
    ];
    
    component.filterByStatus();
    
    expect(component.filterOrder[0].estado).toBe('Abierto');
    expect(component.filterStates.status).toBe('asc');
  });

  it('should open modal when loading order info', () => {
    const testOrder = new Order({ id: 1, estado: 'Abierto', idMesa: 1 });
    
    component.loadInfo(testOrder);
    
    expect(component.showOrderModal).toBeTrue();
    expect(component.pedidoModal).toEqual(testOrder);
  });

  it('should not navigate if all required data is present', () => {
    mockLocalStorage({
      'idRestaurant': '1',
      'idCompany': '1',
      'employeeRol': JSON.stringify({ pago: true })
    });

    component.ngOnInit();
    expect(router.navigate).not.toHaveBeenCalled();
  });
});