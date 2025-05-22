import {
  ComponentFixture,
  TestBed,
  waitForAsync,
  fakeAsync,
  tick,
} from '@angular/core/testing';
import { PaymentComponent } from './payments.component';
import { IonicModule } from '@ionic/angular';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { RouterTestingModule } from '@angular/router/testing';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { of } from 'rxjs';
import { Order } from 'src/app/models/Order';
import { OrderService } from 'src/app/services/order.service';
import { OrderLineService } from 'src/app/services/order-line.service';
import { TicketService } from 'src/app/services/ticket.service';
import { ClientService } from 'src/app/services/client.service';
import { PaymentService } from 'src/app/services/payment.service';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { AlertService } from 'src/app/services/alert.service';
import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { Router } from '@angular/router';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { OrderLine } from 'src/app/models/OrderLine';
import { Payment } from 'src/app/models/Payment';
import { Client } from 'src/app/models/Client';
import { Restaurant } from 'src/app/models/Restaurant';

describe('PaymentComponent', () => {
  let component: PaymentComponent;
  let fixture: ComponentFixture<PaymentComponent>;
  let router: Router;
  let localStorageGetItemSpy: jasmine.Spy;

  // Mock services
  let mockOrderService: jasmine.SpyObj<OrderService>;
  let mockOrderLineService: jasmine.SpyObj<OrderLineService>;
  let mockTicketService: jasmine.SpyObj<TicketService>;
  let mockClientService: jasmine.SpyObj<ClientService>;
  let mockPaymentService: jasmine.SpyObj<PaymentService>;
  let mockRestaurantService: jasmine.SpyObj<RestaurantService>;
  let mockAlertService: jasmine.SpyObj<AlertService>;

  // Sample data
  const sampleOrder = new Order({
    id: 1,
    estado: 'Abierto',
    fechaInicio: new Date(),
    fechaFin: new Date(),
    comensales: 2,
    idMesa: 1,
  });

  const sampleOrderLine = new OrderLine({
    id: 1,
    precio: 10,
    cantidad: 2,
  });

  const sampleClient = new Client({
    id: 1,
    razonSocial: 'Test Client',
    cif: 'A12345678',
    direccion: 'Test Address',
    email: 'test@test.com',
  });

  const sampleRestaurant = new Restaurant({
    id: 1,
    nombre: 'Test Restaurant',
    contrasenya: 'password',
    direccion: 'Test Address',
    telefono: '123456789',
    direccionFiscal: 'Fiscal Address 123',
    cif: 'A12345678',
    razonSocial: 'Restaurante Test Sociedad',
    idEmpresa: 1,
  });

  beforeEach(waitForAsync(() => {
    // Initialize mock services
    mockOrderService = jasmine.createSpyObj('OrderService', [
      'putOrderDiners',
      'putOrderStatus',
    ]);
    mockOrderLineService = jasmine.createSpyObj('OrderLineService', [
      'getListOrderLines',
      'putOrderLines',
    ]);
    mockTicketService = jasmine.createSpyObj('TicketService', [
      'generateTicket',
      'generateBill',
    ]);
    mockClientService = jasmine.createSpyObj('ClientService', [
      'findClientByCif',
      'addClient',
    ]);
    mockPaymentService = jasmine.createSpyObj('PaymentService', ['addPayment']);
    mockRestaurantService = jasmine.createSpyObj('RestaurantService', [
      'getRestaurant',
    ]);
    mockAlertService = jasmine.createSpyObj('AlertService', ['show']);

    TestBed.configureTestingModule({
      imports: [
        PaymentComponent,
        IonicModule.forRoot(),
        HttpClientTestingModule,
        RouterTestingModule.withRoutes([
          { path: 'restaurant/tables', component: class {} },
          { path: 'order-admin', component: class {} },
          { path: 'employees', component: class {} },
          { path: 'employees/panel', component: class {} },
          { path: 'loginRestaurant', component: class {} },
          { path: '', component: class {} },
          { path: 'tpv', component: class {} },
        ]),
        ReactiveFormsModule,
        FormsModule,
      ],
      providers: [
        { provide: OrderService, useValue: mockOrderService },
        { provide: OrderLineService, useValue: mockOrderLineService },
        { provide: TicketService, useValue: mockTicketService },
        { provide: ClientService, useValue: mockClientService },
        { provide: PaymentService, useValue: mockPaymentService },
        { provide: RestaurantService, useValue: mockRestaurantService },
        { provide: AlertService, useValue: mockAlertService },
      ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    }).compileComponents();

    fixture = TestBed.createComponent(PaymentComponent);
    component = fixture.componentInstance;
    router = TestBed.inject(Router);

    spyOn(router, 'navigate');

    // Mock localStorage with proper Order serialization
    localStorageGetItemSpy = spyOn(localStorage, 'getItem');
    mockLocalStorage({
      order: JSON.stringify(sampleOrder),
      tableName: 'Mesa 1',
      idRestaurant: '1',
      restaurant: 'Test Restaurant',
      employeeRol: JSON.stringify({
        idEmpresa: 1,
        pago: true,
        tpv: true,
      }),
      idCompany: '1',
    });

    // Mock service responses with proper model instances
    mockRestaurantService.getRestaurant.and.returnValue(of(sampleRestaurant));
    mockOrderService.putOrderDiners.and.returnValue(of(sampleOrder));
    mockOrderService.putOrderStatus.and.returnValue(of(sampleOrder));
    mockTicketService.generateTicket.and.returnValue(
      of({ ticket_url: 'http://test.com/ticket.pdf' })
    );
    mockTicketService.generateBill.and.returnValue(
      of({ ticket_url: 'http://test.com/bill.pdf' })
    );
    mockClientService.findClientByCif.and.returnValue(of(sampleClient));
    mockClientService.addClient.and.returnValue(of(sampleClient));
    mockPaymentService.addPayment.and.returnValue(
      of(new Payment({ cantidad: 20, tipo: 'Efectivo' }))
    );

    fixture.detectChanges();
  }));

  function mockLocalStorage(data: { [key: string]: string }) {
    localStorageGetItemSpy.and.callFake((key: string) => data[key] || null);
  }

  it('should create', fakeAsync(() => {
    // Mockear las respuestas necesarias para la inicialización
    mockOrderLineService.getListOrderLines.and.returnValue(of([]));
    mockRestaurantService.getRestaurant.and.returnValue(of(sampleRestaurant));

    // Llamar manualmente ngOnInit si es necesario
    component.ngOnInit();

    // Procesar operaciones asíncronas
    tick();
    fixture.detectChanges();

    // Verificación
    expect(component).toBeTruthy();
  }));

  it('should load order from localStorage on init', () => {
    expect(component.order.id).toBe(1);
    expect(component.order.estado).toBe('Abierto');
    expect(component.order.comensales).toBe(2);
    expect(component.order.idMesa).toBe(1);
    expect(component.guests).toBe(2);
    expect(component.tableName).toBe('Mesa 1');
  });

  it('should load order lines and calculate total', fakeAsync(() => {
    // Mock the service response
    mockOrderLineService.getListOrderLines.and.returnValue(
      of([sampleOrderLine])
    );

    // Trigger ngOnInit and ionViewWillEnter
    component.ngOnInit();
    component.ionViewWillEnter();

    // Simulate async operations completion
    tick();
    fixture.detectChanges();

    // Verify the results
    expect(component.orderLines.length).toBe(1);
    expect(component.orderLines[0].precio).toBe(10);
    expect(component.orderLines[0].cantidad).toBe(2);
    expect(component.total).toBe(20);
    expect(component.restante).toBe(20);
  }));

  it('should update guests number and save changes', () => {
    component.increaseGuests();
    expect(component.guests).toBe(3);

    component.decreaseGuests();
    expect(component.guests).toBe(2);

    component.confirmGuests();
    expect(mockOrderService.putOrderDiners).toHaveBeenCalledWith(1, 2);
  });

  it('should handle payment validation', () => {
    component.payments = [new Payment({ cantidad: 20, tipo: 'Efectivo' })];
    component.handleValidated();
    expect(mockPaymentService.addPayment).toHaveBeenCalled();
    expect(mockOrderService.putOrderStatus).toHaveBeenCalledWith(1, 'Cerrado');
  });

  // it('should add and delete payments', fakeAsync(() => {
  //   // Configurar valores iniciales
  //   component.total = 20;
  //   component.restante = 20;

  //   // Mockear el servicio de pagos
  //   mockPaymentService.addPayment.and.returnValue(
  //     of(
  //       new Payment({
  //         cantidad: 10,
  //         tipo: 'Efectivo',
  //       })
  //     )
  //   );

  //   // Añadir pago
  //   component.tipo = 'Efectivo';
  //   component.pay = '10';
  //   component.addPayment();
  //   tick(); // Esperar operaciones asíncronas

  //   fixture.detectChanges();

  //   expect(component.payments.length).toBe(1);
  //   expect(component.restante).toBe(10);

  //   // Eliminar pago
  //   component.deletePayment('Efectivo', 10);
  //   tick(); // Esperar operaciones asíncronas

  //   fixture.detectChanges();

  //   expect(component.payments.length).toBe(0);
  //   expect(component.restante).toBe(20);
  // }));

  it('should handle client operations', () => {
    // Search client
    component.clientSearchCif = 'A12345678';
    component.onCifInputChange();
    expect(mockClientService.findClientByCif).toHaveBeenCalledWith('A12345678');

    // Save new client
    component.clientForm.setValue({
      razonSocial: 'Test Client',
      cif: 'A12345678',
      direccion: 'Test Address',
      email: 'test@test.com',
    });
    component.saveNewClient();
    expect(mockRestaurantService.getRestaurant).toHaveBeenCalled();
  });

  it('should cancel order', fakeAsync(() => {
    mockOrderService.putOrderStatus.and.returnValue(of(sampleOrder));

    component.cancelOrder();
    tick();

    expect(mockOrderService.putOrderStatus).toHaveBeenCalledWith(
      1,
      'Cancelado'
    );
  }));
});
