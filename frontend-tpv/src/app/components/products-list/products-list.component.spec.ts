import { TestBed, ComponentFixture, waitForAsync } from '@angular/core/testing';
import { ProductsListComponent } from './products-list.component';
import { ProductService } from 'src/app/services/product.service';
import { OrderLineService } from 'src/app/services/order-line.service';
import { AlertService } from 'src/app/services/alert.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { of, throwError } from 'rxjs';
import { Product } from 'src/app/models/Product';
import { OrderLine } from 'src/app/models/OrderLine';

describe('ProductsListComponent', () => {
  let component: ProductsListComponent;
  let fixture: ComponentFixture<ProductsListComponent>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockProductService: jasmine.SpyObj<ProductService>;
  let mockOrderLineService: jasmine.SpyObj<OrderLineService>;
  let mockAlertService: jasmine.SpyObj<AlertService>;

  beforeEach(waitForAsync(() => {
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockProductService = jasmine.createSpyObj('ProductService', ['getListProductsRestaurant']);
    mockOrderLineService = jasmine.createSpyObj('OrderLineService', ['postOrderLines']);
    mockAlertService = jasmine.createSpyObj('AlertService', ['show']);

    // ✅ Simular valor por defecto para evitar errores en ngOnInit
    mockProductService.getListProductsRestaurant.and.returnValue(of([]));

    TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), CommonModule, ProductsListComponent],
      providers: [
        { provide: Router, useValue: mockRouter },
        { provide: ProductService, useValue: mockProductService },
        { provide: OrderLineService, useValue: mockOrderLineService },
        { provide: AlertService, useValue: mockAlertService },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(ProductsListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  describe('ngOnInit', () => {
    it('should navigate to loginRestaurant if no idRestaurant exists in localStorage', () => {
      spyOn(localStorage, 'getItem').and.returnValue(null);
      component.ngOnInit();
      expect(mockRouter.navigate).toHaveBeenCalledWith(['/loginRestaurant']);
    });

    it('should initialize idRestaurant from localStorage and call listOfProducts', () => {
      spyOn(localStorage, 'getItem').and.callFake((key) => {
        if (key === 'idRestaurant') return '1';
        return null;
      });

      spyOn(component, 'listOfProducts');

      component.ngOnInit();

      expect(component.idRestaurant).toBe(1);
      expect(component.listOfProducts).toHaveBeenCalled();
    });
  });

  describe('listOfProducts', () => {
    it('should load products successfully', () => {
      const mockProducts: Product[] = [
        {
          id: 1,
          nombre: 'Producto A',
          precio: 10,
          activo: true,
          idCategoria: 2,
          imagen: 'https://example.com/product-a.jpg',
          iva: 10,
          idEmpresa: 1001,
        },
        {
          id: 2,
          nombre: 'Producto B',
          precio: 20,
          activo: true,
          idCategoria: 3,
          imagen: 'https://example.com/product-b.jpg',
          iva: 21,
          idEmpresa: 1002,
        },
      ];

      mockProductService.getListProductsRestaurant.and.returnValue(of(mockProducts));

      component.listOfProducts();

      expect(component.products).toEqual(mockProducts);
      expect(component.filteredProducts).toEqual(mockProducts);
    });

  });

  describe('Product Filtering', () => {
    it('should filter products by category', () => {
      component.products = [
        {
          id: 1,
          nombre: 'Producto A',
          precio: 10,
          activo: true,
          idCategoria: 2,
          imagen: 'https://example.com/product-a.jpg',
          iva: 10,
          idEmpresa: 1001,
        },
        {
          id: 2,
          nombre: 'Producto B',
          precio: 20,
          activo: true,
          idCategoria: 3,
          imagen: 'https://example.com/product-b.jpg',
          iva: 21,
          idEmpresa: 1002,
        },
      ];

      component.filterProductsByCategory(2);

      expect(component.filteredProducts).toEqual([
        {
          id: 1,
          nombre: 'Producto A',
          precio: 10,
          activo: true,
          idCategoria: 2,
          imagen: 'https://example.com/product-a.jpg',
          iva: 10,
          idEmpresa: 1001,
        },
      ]);
    });

    it('should reset filter when categoryId is undefined', () => {
      component.categoryId = undefined;
      component.ngOnChanges();
      expect(component.filteredProducts).toEqual(component.products);
    });
  });

  describe('addProduct', () => {
    it('should add a product to the order', () => {
      const mockProduct: Product = {
        id: 1,
        nombre: 'Producto A',
        precio: 10,
        activo: true,
        idCategoria: 2,
        imagen: 'https://example.com/product-a.jpg',
        iva: 10,
        idEmpresa: 1001,
      };

      spyOn(component.envioProductos, 'emit');
      mockOrderLineService.postOrderLines.and.returnValue(of({} as OrderLine));

      component.currentOrderId = 123;
      component.addProduct(mockProduct);

      expect(mockOrderLineService.postOrderLines).toHaveBeenCalledWith(
        jasmine.objectContaining({
          idPedido: 123,
          idProducto: 1,
          cantidad: 1,
          precio: 10,
          nombre: 'Producto A',
          observaciones: ' ',
          estado: 'Pendiente',
        })
      );

      expect(component.envioProductos.emit).toHaveBeenCalled();
    });

    it('should handle error when adding a product to the order', () => {
      const mockProduct: Product = {
        id: 1,
        nombre: 'Producto A',
        precio: 10,
        activo: true,
        idCategoria: 2,
        imagen: 'https://example.com/product-a.jpg',
        iva: 10,
        idEmpresa: 1001,
      };

      mockOrderLineService.postOrderLines.and.returnValue(throwError(() => new Error('Error añadiendo producto')));
      component.addProduct(mockProduct);
      expect(mockAlertService.show).toHaveBeenCalledWith('Error', 'Error al añadir el producto en el pedido.', 'error');
    });
  });
});
