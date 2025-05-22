import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { ListTableRestaurantComponent } from './list-table-restaurant.component';
import { Router } from '@angular/router';
import { TableService } from 'src/app/services/table.service';
import { OrderService } from 'src/app/services/order.service';
import { LocationService } from 'src/app/services/location.service';
import { ProductService } from 'src/app/services/product.service';
import { OrderLineService } from 'src/app/services/order-line.service';
import { AlertService } from 'src/app/services/alert.service';
import { IonicModule, AlertController } from '@ionic/angular';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { of } from 'rxjs';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { Table } from 'src/app/models/Table';
import { Product } from 'src/app/models/Product';
import { Order } from 'src/app/models/Order';
import { OrderLine } from 'src/app/models/OrderLine';

describe('ListTableRestaurantComponent', () => {
  let component: ListTableRestaurantComponent;
  let fixture: ComponentFixture<ListTableRestaurantComponent>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockTableService: jasmine.SpyObj<TableService>;
  let mockOrderService: jasmine.SpyObj<OrderService>;
  let mockLocationService: jasmine.SpyObj<LocationService>;
  let mockProductService: jasmine.SpyObj<ProductService>;
  let mockOrderLineService: jasmine.SpyObj<OrderLineService>;
  let mockAlertService: jasmine.SpyObj<AlertService>;
  let mockAlertController: jasmine.SpyObj<AlertController>;

  beforeEach(waitForAsync(() => {
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockTableService = jasmine.createSpyObj('TableService', [
      'getTablesByRestaurant', 'getListTableRestaurant', 'updateTable'
    ]);
    mockOrderService = jasmine.createSpyObj('OrderService', [
      'getOrderTable',
      'postOrder',
    ]);
    mockLocationService = jasmine.createSpyObj('LocationService', [
      'getLocations',
    ]);
    mockProductService = jasmine.createSpyObj('ProductService', [
      'getProducts',
      'postOrderLines',
      'getListProductsRestaurant'
    ]);
    mockOrderLineService = jasmine.createSpyObj('OrderLineService', [
      'getOrderLines',
    ]);
    mockAlertService = jasmine.createSpyObj('AlertService', ['show']);
    mockAlertController = jasmine.createSpyObj('AlertController', ['create']);

    TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), CommonModule, FormsModule],
      providers: [
        { provide: Router, useValue: mockRouter },
        { provide: TableService, useValue: mockTableService },
        { provide: OrderService, useValue: mockOrderService },
        { provide: LocationService, useValue: mockLocationService },
        { provide: ProductService, useValue: mockProductService },
        { provide: OrderLineService, useValue: mockOrderLineService },
        { provide: AlertService, useValue: mockAlertService },
        { provide: AlertController, useValue: mockAlertController },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(ListTableRestaurantComponent);
    component = fixture.componentInstance;
  }));

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  describe('ngOnInit', () => {
    it('should navigate to login if no restaurant ID is stored', () => {
      spyOn(localStorage, 'getItem').and.returnValue(null);

      component.ngOnInit();

      expect(mockRouter.navigate).toHaveBeenCalledWith(['/loginRestaurant']);
    });

    it('should initialize restaurant data correctly', () => {
      spyOn(localStorage, 'getItem').and.callFake((key) => {
        if (key === 'idRestaurant') return '1';
        if (key === 'restaurant') return 'Test Restaurant';
        if (key === 'employeeRol')
          return JSON.stringify({ tpv: true } as EmployeeRol);
        return null;
      });

      spyOn(component, 'listOfLocations');
      spyOn(component, 'listOfProducts');
      spyOn(component, 'generateGridCells');

      component.ngOnInit();

      expect(component.idRestaurant).toBe(1);
      expect(component.restaurantName).toBe('Test Restaurant');
      expect(component.listOfLocations).toHaveBeenCalled();
      expect(component.listOfProducts).toHaveBeenCalled();
      expect(component.generateGridCells).toHaveBeenCalled();
    });
  });

  describe('convertirPalabrasANumero', () => {
    it('should convert number words to numeric values', () => {
      expect(component.convertirPalabrasANumero('uno dos tres')).toBe('1 2 3');
      expect(component.convertirPalabrasANumero('mesa veinte')).toBe('mesa 20');
    });

    it('should leave unknown words unchanged', () => {
      expect(component.convertirPalabrasANumero('mesa azul')).toBe('mesa azul');
    });
  });

  describe('iniciarEscucha', () => {
    it('should start speech recognition if available', () => {
      component.speechRecognitionAvailable = true;
      component.recognition = jasmine.createSpyObj('SpeechRecognition', [
        'start',
        'stop',
      ]);

      component.iniciarEscucha();

      expect(component.comandoVoz).toBe('');
      expect(component.recognition.start).toHaveBeenCalled();

      component.ngOnDestroy(); // 🔹 Ejecuta la limpieza manualmente
      expect(component.recognition.stop).toHaveBeenCalled(); // 🔹 Verifica que `stop()` fue llamado
    });

    it('should show an alert if speech recognition is unavailable', () => {
      component.speechRecognitionAvailable = false;
      component.iniciarEscucha();

      expect(mockAlertService.show).toHaveBeenCalledWith(
        'Lo sentimos',
        'En estos momentos, esta funcion no está disponible.',
        'warning'
      );
    });
  });

  describe('procesarComandoVoz', () => {
    beforeEach(() => {
      component.listAllTables = [{ id: 1, mesa: 'mesa 1' } as Table];
      component.products = [{ id: 10, nombre: 'hamburguesa' } as Product];
    });

    it('should show an alert if voice command is empty', () => {
      component.comandoVoz = '';
      component.procesarComandoVoz();

      expect(mockAlertService.show).toHaveBeenCalledWith(
        'Ups...',
        'No he podido entender lo que has dicho.',
        'info'
      );
    });

    it('should find a table and a product, then call crearLineaPedidoVoz', () => {
      spyOn(component, 'crearLineaPedidoVoz');

      component.comandoVoz = 'añade una hamburguesa a mesa 1';
      component.procesarComandoVoz();

      expect(component.crearLineaPedidoVoz).toHaveBeenCalledWith(1, 10, 1);
    });
  });

  describe('ngOnDestroy', () => {
    it('should clean up subscriptions and stop speech recognition', () => {
      spyOn(component['ngUnsubscribe'], 'next');
      spyOn(component['ngUnsubscribe'], 'complete');
      spyOn(component.recognition, 'stop');

      component.ngOnDestroy();

      expect(component['ngUnsubscribe'].next).toHaveBeenCalledWith(null);
      expect(component['ngUnsubscribe'].complete).toHaveBeenCalled();
      expect(component.recognition.stop).toHaveBeenCalled();
    });
  });

  describe('ListTableRestaurantComponent - Order Management & Voice Commands', () => {
    describe('crearLineaPedidoVoz', () => {
      it('should create a new order when no order exists and add order line', () => {
        // Primero limpia cualquier spy existente
        (
          component['orderService'].getOrderTable as jasmine.Spy
        ).and.returnValue(of(null)); // Or of(undefined)
        (component['orderService'].postOrder as jasmine.Spy).and.returnValue(
          of({ id: 100 } as Order)
        );
        const orderService = TestBed.inject(OrderService);
        spyOn(component, 'addLineByVoice');

        component.crearLineaPedidoVoz(1, 10, 2);

        expect(orderService.getOrderTable).toHaveBeenCalledWith(1);
        expect(orderService.postOrder).toHaveBeenCalled();
        expect(component.addLineByVoice).toHaveBeenCalledWith(100, 10, 2);
      });

      it('should add order line when an order already exists', () => {
        const orderService = TestBed.inject(OrderService);
        (
          component['orderService'].getOrderTable as jasmine.Spy
        ).and.returnValue(of({ id: 50 } as Order)); // Or of(undefined)
        spyOn(component, 'addLineByVoice');

        component.crearLineaPedidoVoz(1, 10, 2);

        expect(orderService.getOrderTable).toHaveBeenCalledWith(1);
        expect(component.addLineByVoice).toHaveBeenCalledWith(50, 10, 2);
      });
    });

    describe('addLineByVoice', () => {

      it('should show an error alert when product is not found', () => {
        spyOn(component, 'showAlert');

        component.addLineByVoice(1, 99, 2); // Producto con ID no existente

        expect(component.showAlert).toHaveBeenCalledWith(
          'Ups...',
          'No he podido entender lo que has dicho'
        );
      });
    });

    describe('showAlert', () => {
      it('should create and present an alert', async () => {
        (mockAlertController.create as jasmine.Spy).and.returnValue(
          Promise.resolve({
            present: jasmine.createSpy('present'),
          } as any)
        );

        await component.showAlert('Título', 'Mensaje');

        expect(mockAlertController.create).toHaveBeenCalledWith({
          header: 'Título',
          message: 'Mensaje',
          buttons: ['OK'],
        });
      });
    });

    describe('toggleListen', () => {
      it('should start listening when toggled on', () => {
        spyOn(component, 'iniciarEscucha');

        component.toggleListen();

        expect(component.isListening).toBeTrue();
        expect(component.iniciarEscucha).toHaveBeenCalled();
      });

      it('should stop recognition when toggled off', () => {
        component.isListening = true;
        component.speechRecognitionAvailable = true;
        component.recognition = jasmine.createSpyObj('SpeechRecognition', [
          'stop',
        ]);

        component.toggleListen();

        expect(component.isListening).toBeFalse();
        expect(component.recognition.stop).toHaveBeenCalled();
      });
    });

  });

  describe('ListTableRestaurantComponent - Location & Table Management', () => {
  

  describe('loadTablesByLocation', () => {
    it('should fetch tables and filter active ones by location', () => {
      (component['tableService'].getListTableRestaurant as jasmine.Spy).and.returnValue(
           of([
    { id: 1, activo: true, idUbicacion: 1, mesa: 'Mesa 1', pos_x: 2, pos_y: 3, estado: 'libre', nComensales: 0 },
    { id: 2, activo: false, idUbicacion: 1, mesa: 'Mesa 2', pos_x: 1, pos_y: 3, estado: 'libre', nComensales: 0 },
    { id: 3, activo: true, idUbicacion: 2, mesa: 'Mesa 3', pos_x: 2, pos_y: 1, estado: 'libre', nComensales: 0 }
  ])
);

      spyOn(component, 'showStatus');

      component.loadTablesByLocation(1);

      expect(component['tableService'].getListTableRestaurant).toHaveBeenCalledWith(component.idRestaurant);
      expect(component.listAllTables.length).toBe(2);
      expect(component.listTables.length).toBe(1);
      expect(component.showStatus).toHaveBeenCalled();
    });
  });

  describe('listOfTables', () => {
    it('should fetch all active tables and sort them', () => {
      (component['tableService'].getListTableRestaurant as jasmine.Spy).and.returnValue(
  of([
    { id: 1, activo: true, idUbicacion: 1, mesa: 'Mesa 1', pos_x: 2, pos_y: 3, estado: 'libre', nComensales: 0 },
    { id: 2, activo: false, idUbicacion: 1, mesa: 'Mesa 2', pos_x: 1, pos_y: 3, estado: 'libre', nComensales: 0 },
    { id: 3, activo: true, idUbicacion: 2, mesa: 'Mesa 3', pos_x: 2, pos_y: 1, estado: 'libre', nComensales: 0 }
  ])
      );
      spyOn(component, 'showStatus');

      component.listOfTables();

      expect(component['tableService'].getListTableRestaurant).toHaveBeenCalledWith(component.idRestaurant);
      expect(component.listTables.length).toBe(2);
      expect(component.filterTables[0].mesa).toBe('Mesa 1');
      expect(component.showStatus).toHaveBeenCalled();
    });
  });

  describe('filterLocation', () => {
    it('should update selected location and load tables when location changes', () => {
      spyOn(component, 'loadTablesByLocation');

      component.filterLocation(2);

      expect(component.selectedLocationId).toBe(2);
      expect(component.loadTablesByLocation).toHaveBeenCalledWith(2);
    });

    it('should do nothing if the location remains the same', () => {
      component.selectedLocationId = 1;
      spyOn(component, 'loadTablesByLocation');

      component.filterLocation(1);

      expect(component.loadTablesByLocation).not.toHaveBeenCalled();
    });
  });

  describe('showStatus', () => {
    it('should update table statuses based on existing orders', () => {
      component.filterTables = [{ id: 1, mesa: 'Mesa 1' }] as Table[];
      (component['orderService'].getOrderTable as jasmine.Spy).and.returnValue(of(new Order({
  id: 1,
  comensales: 4,
  estado: 'activo',
  fechaInicio: new Date(),
  fechaFin: undefined,
  idMesa: 1
})));

      component.showStatus();

      expect(component['orderService'].getOrderTable).toHaveBeenCalledWith(1);
      expect(component.filterTables[0].estado).toBe('ocupada');
      expect(component.filterTables[0].nComensales).toBe(4);
    });

    it('should mark tables as free when no order exists', () => {
      component.filterTables = [{ id: 2, mesa: 'Mesa 2' }] as Table[];
             (component['orderService'].getOrderTable as jasmine.Spy).and.returnValue(
          of(null)
        );


      component.showStatus();

      expect(component.filterTables[0].estado).toBe('libre');
      expect(component.filterTables[0].nComensales).toBe(0);
    });
  });

});

describe('ListTableRestaurantComponent - Guests & Drag-and-Drop Management', () => {

  describe('increaseGuests', () => {
    it('should increase guest count', () => {
      component.guests = 2;
      component.increaseGuests();
      expect(component.guests).toBe(3);
    });
  });

  describe('decreaseGuests', () => {
    it('should decrease guest count when guests are more than 1', () => {
      component.guests = 3;
      component.decreaseGuests();
      expect(component.guests).toBe(2);
    });

    it('should not decrease guest count below 1', () => {
      component.guests = 1;
      component.decreaseGuests();
      expect(component.guests).toBe(1);
    });
  });

  describe('confirmGuests', () => {
    it('should create a new order', () => {
      component.guests = 4;
      component.idTableSelected = 1;
      (component['orderService'].postOrder as jasmine.Spy).and.returnValue(of(new Order({
        id: 100,
        comensales: 4,
        estado: 'activo',
        fechaInicio: new Date(),
        fechaFin: undefined,
        idMesa: 1
      })));

      component.confirmGuests(1);

      expect(component['orderService'].postOrder).toHaveBeenCalled();
      expect(component.showGuestsModal).toBeFalse();
    });
  });

  describe('getTablesAt', () => {
    it('should return tables at given position', () => {
      component.filterTables = [
        { id: 1, mesa: 'Mesa 1', pos_x: 2, pos_y: 3 },
        { id: 2, mesa: 'Mesa 2', pos_x: 2, pos_y: 3 },
        { id: 3, mesa: 'Mesa 3', pos_x: 1, pos_y: 2 }
      ] as Table[];

      const result = component.getTablesAt(3, 2);
      expect(result.length).toBe(2);
      expect(result[0].mesa).toBe('Mesa 1');
      expect(result[1].mesa).toBe('Mesa 2');
    });
  });

  describe('generateGridCells', () => {
    it('should generate an 8x10 grid', () => {
      component.generateGridCells();
      expect(component.gridCells.length).toBe(8 * 10);
      expect(component.gridCells[0]).toEqual({ row: 0, col: 0 });
      expect(component.gridCells[79]).toEqual({ row: 7, col: 9 });
    });
  });


  describe('listOfProducts', () => {
    it('should fetch list of products from service', () => {
      (component['productService'].getListProductsRestaurant as jasmine.Spy).and.returnValue(
        of([{ id: 10, nombre: 'Hamburguesa' }] as Product[])
      );

      component.listOfProducts();

      expect(component.products.length).toBe(1);
      expect(component.products[0].nombre).toBe('Hamburguesa');
    });

    it('should handle errors when fetching products', () => {
    (component['productService'].getListProductsRestaurant as jasmine.Spy).and.returnValue(of(undefined));

      component.listOfProducts();

      // No navegación por error en este caso
      expect(component.products).toBeUndefined();
    });
  });

});

});
