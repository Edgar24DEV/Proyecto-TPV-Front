import { TestBed } from '@angular/core/testing';
import { ProductService } from './product.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Product } from '../models/Product';
import { environment } from 'src/environments/environment';

describe('ProductService', () => {
  let service: ProductService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        ProductService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(ProductService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should get list of products by restaurant id', () => {
    const mockProducts = [
      { id: 1, nombre: 'Product 1', precio: 10.99, imagen: 'image1.jpg', activo: true, iva: 21, idCategoria: 1, idEmpresa: 1 },
      { id: 2, nombre: 'Product 2', precio: 15.50, imagen: 'image2.jpg', activo: true, iva: 10, idCategoria: 2, idEmpresa: 1 }
    ];

    service.getListProductsRestaurant(1).subscribe(products => {
      expect(products.length).toBe(2);
      expect(products[0]).toBeInstanceOf(Product);
      expect(products[1].nombre).toBe('Product 2');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/products?id_restaurante=1`);
    expect(req.request.method).toBe('GET');
    req.flush({ data: mockProducts }); // Backend sends data in 'data' field
  });

  it('should handle error when server response is not an array for getListProductsRestaurant', () => {
    service.getListProductsRestaurant(1).subscribe({
      error: (err) => {
        expect(err.message).toBe('La respuesta del servidor no es una lista');
      }
    });

    const req = httpMock.expectOne(`${mockApiUrl}/products?id_restaurante=1`);
    req.flush({ data: {} }); // Backend sends data in 'data' field
  });

  it('should get list of products by company id', () => {
    const mockProducts = [
      { id: 1, nombre: 'Company Product 1', precio: 20.99, imagen: 'company1.jpg', activo: true, iva: 21, idCategoria: 1, idEmpresa: 1 }
    ];

    service.getListProductsCompany(1).subscribe(products => {
      expect(products.length).toBe(1);
      expect(products[0]).toBeInstanceOf(Product);
      expect(products[0].nombre).toBe('Company Product 1');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/company/products?id_empresa=1`);
    expect(req.request.method).toBe('GET');
    req.flush({ data: mockProducts }); // Backend sends data in 'data' field
  });

  it('should get all products by restaurant id', () => {
    const mockProducts = [
      { id: 1, nombre: 'Restaurant Product 1', precio: 12.99, imagen: 'rest1.jpg', activo: true, iva: 21, idCategoria: 1, idEmpresa: 1 }
    ];

    service.getAllProductsRestaurant(1).subscribe(products => {
      expect(products.length).toBe(1);
      expect(products[0]).toBeInstanceOf(Product);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant/products?id_restaurante=1`);
    expect(req.request.method).toBe('GET');
    req.flush({ data: mockProducts }); // Backend sends data in 'data' field
  });

  it('should get single product by id', () => {
    const mockProduct = { id: 1, nombre: 'Single Product', precio: 9.99, imagen: 'single.jpg', activo: true, iva: 21, idCategoria: 1, idEmpresa: 1 };

    service.getProduct(1).subscribe(product => {
      expect(product).toBeInstanceOf(Product);
      expect(product.nombre).toBe('Single Product');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/product?id=1`);
    expect(req.request.method).toBe('GET');
    req.flush({ data: mockProduct }); // Backend sends data in 'data' field
  });

  it('should create new product', () => {
    const newProduct = new Product({ nombre: 'New Product', precio: 19.99, imagen: 'new.jpg', iva: 21, idCategoria: 1 });
    const mockResponse = { id: 123, nombre: 'New Product', precio: 19.99, imagen: 'new.jpg', iva: 21, idCategoria: 1, idEmpresa: 1 };

    service.postProduct(newProduct, 1).subscribe(product => {
      expect(product).toBeInstanceOf(Product);
      expect(product.nombre).toBe('New Product');
      expect(product.id).toBe(123); // Ensure the ID is set from the response
    });

    const req = httpMock.expectOne(`${mockApiUrl}/products`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({ nombre: 'New Product', precio: 19.99, imagen: 'new.jpg', iva: 21, id_categoria: 1, id_empresa: 1 });
    req.flush({ data: mockResponse }); // Backend sends data in 'data' field
  });

  it('should handle invalid response when creating product', () => {
    const newProduct = new Product({ nombre: 'Invalid Product', precio: 19.99, imagen: 'invalid.jpg', iva: 21, idCategoria: 1 });

    service.postProduct(newProduct, 1).subscribe({
      error: (err) => {
        expect(err.message).toBe('La respuesta del servidor no contiene un empleado válido');
      }
    });

    const req = httpMock.expectOne(`${mockApiUrl}/products`);
    req.flush({ data: {} }); // Backend sends data in 'data' field
  });

  it('should get product-restaurant relation', () => {
    const mockResponse = { active: true };

    service.getProductRestaurant(1, 1).subscribe(response => {
      expect(response).toEqual(mockResponse);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant/product?id_restaurante=1&id_producto=1`);
    expect(req.request.method).toBe('GET');
    req.flush(mockResponse); // Backend sends direct response
  });

  it('should create product-restaurant relation', () => {
  const product = new Product({ id: 1 });
  const idRestaurant = 1;

  service.postProductRestaurant(product, idRestaurant).subscribe(response => {
    expect(response).toBeUndefined(); // Espera undefined
  });

  const req = httpMock.expectOne(`${mockApiUrl}/restaurant-product`);
  expect(req.request.method).toBe('POST');
  expect(req.request.body).toEqual({
    id_producto: 1,
    activo: undefined,
    id_restaurante: 1
  });
  req.flush({}); // Simula una respuesta exitosa (el cuerpo no importa mucho si no lo usas)
});

it('should update product-restaurant relation', () => {
  const product = new Product({ id: 1 });
  const active = true;
  const idRestaurant = 1;

  service.putProductRestaurant(product, active, idRestaurant).subscribe(response => {
    expect(response).toBeUndefined(); // Espera undefined
  });

  const req = httpMock.expectOne(`${mockApiUrl}/restaurant/product`);
  expect(req.request.method).toBe('PUT');
  expect(req.request.body).toEqual({
    id_producto: 1,
    activo: true,
    id_restaurante: 1
  });
  req.flush({}); // Simula una respuesta exitosa
});

  it('should upload image', () => {
    const mockResponse = { path: 'uploads/test.jpg' };
    const mockFormData = new FormData();
    mockFormData.append('image', new Blob(), 'test.jpg');

    service.uploadImage(mockFormData).subscribe(response => {
      expect(response).toEqual(mockResponse);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/upload`);
    expect(req.request.method).toBe('POST');
    req.flush(mockResponse); // Backend sends direct response
  });

  it('should update product', () => {
    const updatedProduct = new Product({ id: 1, nombre: 'Updated Product', precio: 29.99, imagen: 'updated.jpg', iva: 10, activo: true, idCategoria: 2 });
    const mockResponse = { data: updatedProduct };

    service.putProduct(updatedProduct).subscribe(product => {
      expect(product).toBeInstanceOf(Product);
      expect(product.nombre).toBe('Updated Product');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/product`);
    expect(req.request.method).toBe('PUT');
    expect(req.request.body).toEqual({ id: 1, nombre: 'Updated Product', precio: 29.99, imagen: 'updated.jpg', iva: 10, activo: true, id_categoria: 2 });
    req.flush(mockResponse); // Backend sends data in 'data' field
  });

  it('should delete product', () => {
  const mockResponse: Product = {
    id: 1,
    nombre: 'Deleted Product',
    precio: 0,
    imagen: '',
    activo: false,
    iva: 0,
    idCategoria: 0,
    idEmpresa: 0
  };

  service.deleteProduct(1).subscribe(response => {
    expect(response).toEqual(mockResponse);
  });

  const req = httpMock.expectOne(`${mockApiUrl}/product?id=1`);
  expect(req.request.method).toBe('DELETE');
  req.flush(mockResponse);
});

  it('should create product-restaurant relation with postProductRestaurantRelation', () => {
    const mockResponse = {};

    service.postProductRestaurantRelation(1, 1).subscribe(response => {
      expect(response).toEqual(mockResponse);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant-product`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({ activo: true, id_producto: 1, id_restaurante: 1 });
    req.flush(mockResponse); // Backend sends direct response
  });
});