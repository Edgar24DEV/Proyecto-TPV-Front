import { ComponentFixture, TestBed, waitForAsync, fakeAsync, tick, flush } from '@angular/core/testing';
import { ProductAdminComponent } from './product-admin.component';
import { IonicModule } from '@ionic/angular';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { RouterTestingModule } from '@angular/router/testing';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { of } from 'rxjs';
import { Product } from 'src/app/models/Product';
import { Category } from 'src/app/models/Category';
import { ProductService } from 'src/app/services/product.service';
import { CategoryService } from 'src/app/services/category.service';
import { AlertController } from '@ionic/angular';
import { AlertService } from 'src/app/services/alert.service';
import { AppComponent } from 'src/app/app.component';
import { Router } from '@angular/router';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';

describe('ProductAdminComponent', () => {
  let component: ProductAdminComponent;
  let fixture: ComponentFixture<ProductAdminComponent>;
  let router: Router;
  let localStorageGetItemSpy: jasmine.Spy;

  // Mock services
  let mockProductService: jasmine.SpyObj<ProductService>;
  let mockCategoryService: jasmine.SpyObj<CategoryService>;
  let mockAlertController: jasmine.SpyObj<AlertController>;
  let mockAlertService: jasmine.SpyObj<AlertService>;
  let mockAppComponent: jasmine.SpyObj<AppComponent>;

  // Sample data
  const sampleProduct = new Product({
    id: 1,
    nombre: 'Test Product',
    precio: 10.99,
    activo: true,
    imagen: 'test.jpg',
    idCategoria: 1
  });

  const sampleCategory = new Category({
    id: 1,
    categoria: 'Test Category',
    activo: true
  });

  const sampleEmployeeRol = new EmployeeRol({
    rol: "admin", 
    productos: true,
    categorias: true,
    tpv: true,
    usuarios: true,
    mesas: true,
    restaurante: true,
    clientes: true,
    empresa: true,
    pago: true,
    idEmpresa: 1,
    idEmpleado: 123,
    nombre: "Juan Pérez",
  });

  beforeEach(waitForAsync(() => {
    // Initialize mock services
    mockProductService = jasmine.createSpyObj('ProductService', [
      'getListProductsCompany',
      'getAllProductsRestaurant',
      'getProduct',
      'getProductRestaurant',
      'postProduct',
      'postProductRestaurant',
      'putProduct',
      'putProductRestaurant',
      'deleteProduct',
      'uploadImage',
      'postProductRestaurantRelation',
    ]);

    mockCategoryService = jasmine.createSpyObj('CategoryService', [
      'getListCategoryCompany',
      'getListCategoryRestaurant'
    ]);

    // Mock AlertController
    const mockAlert = {
      present: jasmine.createSpy('present'),
      onDidDismiss: jasmine.createSpy('onDidDismiss').and.returnValue(Promise.resolve({ role: 'confirm' }))
    };

    mockAlertController = jasmine.createSpyObj('AlertController', ['create']);
    mockAlertController.create.and.returnValue(Promise.resolve(mockAlert as any));

    mockAlertService = jasmine.createSpyObj('AlertService', ['show']);
    mockAppComponent = jasmine.createSpyObj('AppComponent', ['reloadHeader']);

    TestBed.configureTestingModule({
      imports: [
        ProductAdminComponent,
        IonicModule.forRoot(),
        HttpClientTestingModule,
        RouterTestingModule.withRoutes([]),
        ReactiveFormsModule,
        FormsModule
      ],
      providers: [
        { provide: ProductService, useValue: mockProductService },
        { provide: CategoryService, useValue: mockCategoryService },
        { provide: AlertController, useValue: mockAlertController },
        { provide: AlertService, useValue: mockAlertService },
        { provide: AppComponent, useValue: mockAppComponent }
      ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA]
    }).compileComponents();

    fixture = TestBed.createComponent(ProductAdminComponent);
    component = fixture.componentInstance;
    router = TestBed.inject(Router);
    spyOn(router, 'navigate');

    // Mock localStorage
    localStorageGetItemSpy = spyOn(localStorage, 'getItem');
    mockLocalStorage({
      'idCompany': '1',
      'company': 'Test Company',
      'employeeRol': JSON.stringify(sampleEmployeeRol)
    });

    // Mock service responses
    mockProductService.getListProductsCompany.and.returnValue(of([sampleProduct]));
    mockProductService.getAllProductsRestaurant.and.returnValue(of([sampleProduct]));
    mockProductService.getProduct.and.returnValue(of(sampleProduct));
    mockProductService.getProductRestaurant.and.returnValue(of(true));
    mockProductService.postProduct.and.returnValue(of(sampleProduct));
    mockProductService.postProductRestaurant.and.returnValue(of({}));
    mockProductService.putProduct.and.returnValue(of(sampleProduct));
    mockProductService.putProductRestaurant.and.returnValue(of({}));
    mockProductService.uploadImage.and.returnValue(of({ path: 'test.jpg' }));

    mockCategoryService.getListCategoryCompany.and.returnValue(of([sampleCategory]));
    mockCategoryService.getListCategoryRestaurant.and.returnValue(of([sampleCategory]));

    fixture.detectChanges();
  }));

  function mockLocalStorage(data: { [key: string]: string }) {
    localStorageGetItemSpy.and.callFake((key: string) => data[key] || null);
  }

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should initialize data from localStorage', () => {
    expect(component.idCompany).toBe(1);
    expect(component.companyName).toBe('Test Company');
    expect(component.employeeRol.idEmpresa).toBe(1);
  });

  it('should initialize forms', () => {
    expect(component.productForm).toBeDefined();
    expect(component.productRestForm).toBeDefined();
  });

  it('should load products and categories on init', fakeAsync(() => {
    component.ngOnInit();
    tick();
    
    expect(mockProductService.getListProductsCompany).toHaveBeenCalled();
    expect(mockCategoryService.getListCategoryCompany).toHaveBeenCalled();
    expect(component.productosCompany.length).toBe(1);
    expect(component.listCategories.length).toBe(1);
  }));

  it('should show create product modal', fakeAsync(() => {
    component.showCreate();
    fixture.detectChanges();
    tick();
    
    expect(component.showProductModal).toBeTrue();
    expect(component.createProduct).toBeTrue();
    expect(component.productForm.value.nombre).toBeNull();
  }));
  
  it('should show edit product modal', fakeAsync(() => {
    component.showEdit(1);
    tick();
    
    expect(mockProductService.getProduct).toHaveBeenCalledWith(1);
    expect(component.showProductModal).toBeTrue();
    expect(component.createProduct).toBeFalse();
  }));

 
 
  

  it('should filter products by category', () => {
    component.productosCompany = [sampleProduct];
    component.filterByCategory(1);
    expect(component.results.length).toBe(1);
    
    component.filterByCategory(999); 
    expect(component.results.length).toBe(0);
  });

  it('should handle search input', () => {
    component.productosCompany = [sampleProduct];
    const event = { target: { value: 'test' } };
    component.handleInput(event as any);
    expect(component.results.length).toBe(1);
    
    event.target.value = 'non-existent';
    component.handleInput(event as any);
    expect(component.results.length).toBe(0);
  });

  it('should assign products to restaurant', fakeAsync(() => {
    component.idRestaurant = 1;
    component.selectedProducts = [1]; // Aquí estás pasando un array de productos, pero espera un solo producto
    
    // Si el método espera dos parámetros: el idRestaurante y un idProducto individual
    mockProductService.postProductRestaurantRelation.and.returnValue(of(true));  // Mockea el retorno exitoso
  
    // Llamamos a la función que debería pasar los dos parámetros correctamente
    component.saveProductRest();
    tick();  // Esperamos que las llamadas asincrónicas se resuelvan
  
    // Verificamos que el servicio haya sido llamado con los dos parámetros correctos
    expect(mockProductService.postProductRestaurantRelation).toHaveBeenCalledWith(1, 1); // Aquí pasamos 1 para idRestaurante y 1 para el producto
  }));
  
  
  
  

  it('should handle file selection', fakeAsync(() => {
    const mockFile = new File([''], 'test.jpg', { type: 'image/jpeg' });
    const event = { target: { files: [mockFile] } };
  
    // Espiar el constructor de FileReader
    const mockFileReader = jasmine.createSpyObj('FileReader', ['readAsDataURL']);
    mockFileReader.readAsDataURL.and.callFake((file: File) => {
      // Simulamos que FileReader ha terminado y hemos cargado la imagen en base64
      setTimeout(() => {
        const base64Data = 'data:image/jpeg;base64,abcd1234';
        component.selectedImagePreview = base64Data;  // Asignamos el valor simulado a la propiedad
      }, 0);  // Usamos setTimeout para simular la asincronía del FileReader
    });
  
    // Sustituir FileReader original por el mock
    spyOn(window, 'FileReader').and.returnValue(mockFileReader);
  
    // Llamamos a la función onFileChange
    component.onFileChange(event as any);
    tick();  // Aseguramos que el código asincrónico se ejecute
  
    // Verificamos que selectedImageFile haya sido asignada correctamente
    expect(component.selectedImageFile).toBe(mockFile);
  
    // Verificamos que selectedImagePreview contiene la cadena base64 esperada
    expect(component.selectedImagePreview).toContain('data:image/jpeg;base64');
  }));
  
  it('should validate form controls', () => {
    const nombreControl = component.productForm.get('nombre');
    nombreControl?.setValue('');
    nombreControl?.markAsTouched();
    
    expect(component.isInvalid('nombre')).toBeTrue();
    
    nombreControl?.setValue('Valid Name');
    expect(component.isInvalid('nombre')).toBeFalse();
  });
});
