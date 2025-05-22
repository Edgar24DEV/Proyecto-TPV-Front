import { TestBed } from '@angular/core/testing';
import { OrderLineService } from './order-line.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { OrderLine } from '../models/OrderLine';
import { environment } from 'src/environments/environment';
import { Order } from '../models/Order';

describe('OrderLineService', () => {
  let service: OrderLineService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        OrderLineService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(OrderLineService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  describe('getListOrderLines', () => {
    it('should get list of order lines by order id', () => {
      const mockOrderLines = [
        {
          id: 1,
          idPedido: 10,
          idProducto: 5,
          cantidad: 2,
          precio: 15.99,
          nombre: 'Product 1',
          observaciones: 'No onions',
          estado: 'Pendiente'
        },
        {
          id: 2,
          idPedido: 10,
          idProducto: 8,
          cantidad: 1,
          precio: 12.50,
          nombre: 'Product 2',
          observaciones: '',
          estado: 'Pendiente'
        }
      ];

      service.getListOrderLines(10).subscribe(orderLines => {
        expect(orderLines.length).toBe(2);
        expect(orderLines[0]).toBeInstanceOf(OrderLine);
        expect(orderLines[0].id).toBe(1);
        expect(orderLines[1].nombre).toBe('Product 2');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/orders?id_pedido=10`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockOrderLines });
    });

    it('should throw error when response is not an array', () => {
      service.getListOrderLines(10).subscribe({
        error: (err) => {
          expect(err.message).toBe('La respuesta del servidor no es una lista');
        }
      });

      const req = httpMock.expectOne(`${mockApiUrl}/orders?id_pedido=10`);
      req.flush({ data: {} }); // Non-array response
    });
  });

  describe('putOrderLines', () => {
    it('should update an order line', () => {
      const orderLine = new OrderLine({
        id: 1,
        cantidad: 3,
        precio: 15.99,
        nombre: 'Updated Product'
      });
      const mockResponse = new OrderLine( {
        id: 1,
        idPedido: 10,
        idProducto: 5,
        cantidad: 3,
        precio: 15.99,
        nombre: 'Updated Product',
        observaciones: 'No onions',
        estado: 'Pendiente'
      });

      service.putOrderLines(orderLine).subscribe(updatedLine => {
        expect(updatedLine).toBeInstanceOf(OrderLine);
        expect(updatedLine.cantidad).toBe(3);
        expect(updatedLine.nombre).toBe('Updated Product');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order-line`);
      expect(req.request.method).toBe('PUT');
      expect(req.request.body).toEqual({
        id: '1',
        cantidad: 3,
        precio: '15.99',
        nombre: 'Updated Product'
      });
      req.flush(mockResponse);
    });
  });

  describe('postOrderLines', () => {
    it('should create a new order line', () => {
      const newOrderLine = new OrderLine({
        idPedido: 10,
        idProducto: 5,
        cantidad: 2,
        observaciones: 'Extra sauce'
      });
      const mockResponse = new OrderLine( {
        id: 3,
        idPedido: 10,
        idProducto: 5,
        cantidad: 2,
        precio: 15.99,
        nombre: 'New Product',
        observaciones: 'Extra sauce',
        estado: 'Pendiente'
      });

      service.postOrderLines(newOrderLine).subscribe(createdLine => {
        expect(createdLine).toBeInstanceOf(OrderLine);
        expect(createdLine.idPedido).toBe(10);
        expect(createdLine.estado).toBe('Pendiente');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order-line`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({
        id_pedido: 10,
        id_producto: 5,
        cantidad: 2,
        observaciones: 'Extra sauce',
        estado: 'Pendiente'
      });
      req.flush(mockResponse);
    });

    it('should use default quantity if not provided', () => {
      const newOrderLine = new OrderLine({
        idPedido: 10,
        idProducto: 5,
        observaciones: 'No onions'
      });

      service.postOrderLines(newOrderLine).subscribe();

      const req = httpMock.expectOne(`${mockApiUrl}/order-line`);
      expect(req.request.body.cantidad).toBe(1); // Default quantity
      req.flush({});
    });
  });

  describe('deleteOrderLine', () => {
    it('should delete an order line', () => {
      const orderLineId = 1;

      service.deleteOrderLine(orderLineId).subscribe(response => {
        expect(response).toBeTruthy();
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order-line`);
      expect(req.request.method).toBe('DELETE');
      expect(req.request.body).toEqual({ id: 1 });
      req.flush({ success: true });
    });
  });
});