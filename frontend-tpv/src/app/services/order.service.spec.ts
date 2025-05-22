import { TestBed } from '@angular/core/testing';
import { OrderService } from './order.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Order } from '../models/Order';
import { environment } from 'src/environments/environment';

describe('OrderService', () => {
  let service: OrderService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        OrderService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(OrderService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  describe('getOrderTable', () => {
    it('should get order by table id', () => {
      const mockOrderData = {
        id: 10,
        estado: 'pendiente',
        fecha_inicio: '2025-05-15T11:00:00.000Z',
        fecha_fin: null,
        comensales: 2,
        id_mesa: 5,
      };

      service.getOrderTable(5).subscribe(order => {
        expect(order).toBeInstanceOf(Order);
        expect(order.id).toBe(10);
        expect(order.estado).toBe('pendiente');
        expect(order.fechaInicio?.toISOString()).toBe('2025-05-15T09:00:00.000Z');
        expect(order.fechaFin).toBeUndefined(); // Since null becomes undefined in your class
        expect(order.comensales).toBe(2);
        expect(order.idMesa).toBe(5);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order/get-order?id_mesa=5`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockOrderData });
    });

    it('should handle null fecha_fin', () => {
      const mockOrderData = {
        id: 10,
        estado: 'pendiente',
        fecha_inicio: '2025-05-15T11:00:00.000Z',
        fecha_fin: null,
        comensales: 2,
        id_mesa: 5,
      };

      service.getOrderTable(5).subscribe(order => {
        expect(order.fechaFin).toBeUndefined(); // Your class converts null to undefined
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order/get-order?id_mesa=5`);
      req.flush({ data: mockOrderData });
    });
  });

  describe('postOrder', () => {
    it('should create a new order and return Order instance', () => {
      const mockOrderToSend = new Order({ comensales: 3, idMesa: 7 });
      const mockResponse = new Order ({
        id: 10,
        estado: 'activo',
        fechaInicio: '2025-05-15T11',
        fechaFin: undefined,
        comensales: 3,
        idMesa: 7,
    });

      service.postOrder(mockOrderToSend).subscribe(order => {
        expect(order).toBeInstanceOf(Order);
        expect(order.id).toBe(10);
        expect(order.estado).toBe('activo');
        expect(order.fechaFin).toBeUndefined();
        expect(order.comensales).toBe(3);
        expect(order.idMesa).toBe(7);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({ comensales: 3, idMesa: 7 });
      req.flush(mockResponse);
    });
  });

  describe('putOrderDiners', () => {
    it('should update order diners', () => {
      const orderId = 15;
      const newDiners = 4;
      const mockResponse = new Order ({
        id: 15,
        estado: 'servido',
        fechaInicio: '2025-05-15T11',
        fechaFin: undefined,
        comensales: 4,
        idMesa: 5,
    });

      service.putOrderDiners(orderId, newDiners).subscribe(order => {
        expect(order).toBeInstanceOf(Order);
        expect(order.comensales).toBe(4);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order`);
      expect(req.request.method).toBe('PUT');
      expect(req.request.body).toEqual({
        id: orderId.toString(),
        comensales: newDiners.toString()
      });
      req.flush(mockResponse);
    });
  });

  describe('putOrderStatus', () => {
    it('should update order status', () => {
      const orderId = 10;
      const newStatus = 'servido';
      const mockResponse = new Order ({
        id: 10,
        estado: 'servido',
        fechaInicio: '2025-05-15T11',
        fechaFin: undefined,
        comensales: 2,
        idMesa: 5,
    });

      service.putOrderStatus(orderId, newStatus).subscribe(order => {
        expect(order).toBeInstanceOf(Order);
        expect(order.estado).toBe('servido');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order-status`);
      expect(req.request.method).toBe('PUT');
      expect(req.request.body).toEqual({
        id: orderId.toString(),
        estado: newStatus
      });
      req.flush(mockResponse);
    });
  });

  describe('getOrdersByRestaurant', () => {
    it('should get orders by restaurant id', () => {
      const restaurantId = 1;
      const mockOrders = [
        {
          id: 20,
          estado: 'activo',
          fecha_inicio: '2025-05-15T10:30:00.000Z',
          fecha_fin: null,
          comensales: 1,
          id_mesa: 1,
        },
        {
          id: 21,
          estado: 'pendiente',
          fecha_inicio: '2025-05-15T10:45:00.000Z',
          fecha_fin: null,
          comensales: 4,
          id_mesa: 2,
        },
      ];

      service.getOrdersByRestaurant(restaurantId).subscribe(orders => {
        expect(orders.length).toBe(2);
        expect(orders[0]).toBeInstanceOf(Order);
        expect(orders[0].id).toBe(20);
        expect(orders[1].id).toBe(21);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/restaurant/orders?id_restaurante=${restaurantId}`);
      expect(req.request.method).toBe('GET');
      req.flush(mockOrders);
    });
  });

  describe('getOrdersByCompany', () => {
    it('should get orders by company id', () => {
      const companyId = 2;
      const mockOrders = [
        {
          id: 30,
          estado: 'cerrado',
          fecha_inicio: '2025-05-14T20:00:00.000Z',
          fecha_fin: '2025-05-14T21:00:00.000Z',
          comensales: 2,
          id_mesa: 3,
        }
      ];

      service.getOrdersByCompany(companyId).subscribe(orders => {
        expect(orders.length).toBe(1);
        expect(orders[0]).toBeInstanceOf(Order);
        expect(orders[0].estado).toBe('cerrado');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/company/orders?id_empresa=${companyId}`);
      expect(req.request.method).toBe('GET');
      req.flush(mockOrders);
    });
  });
});