import { TestBed } from '@angular/core/testing';
import { PaymentService } from './payment.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Payment } from '../models/Payment';
import { environment } from 'src/environments/environment';
import { Client } from '../models/Client';

describe('PaymentService', () => {
  let service: PaymentService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        PaymentService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(PaymentService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  describe('addPayment', () => {
    it('should send POST request with correct payload', () => {
      const mockPayment: Payment = {
        tipo: 'Tarjeta',
        cantidad: 50.00,
        idPedido: 1,
        id: undefined,
        fecha: undefined,
        idCliente: undefined,
        razonSocial: undefined,
        cif: undefined,
        nFactura: undefined,
        correo: undefined,
        direccionFiscal: undefined
      };
      const mockResponse = { success: true, message: 'Pago añadido correctamente' };

      service.addPayment(mockPayment).subscribe(response => {
        expect(response).toEqual(mockResponse);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/payment`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({
        tipo: 'Tarjeta',
        cantidad: 50.00,
        fecha: null,
        id_pedido: 1,
        id_cliente: null,
        razon_social: null,
        cif: null,
        n_factura: null,
        correo: null,
        direccion_fiscal: null,
      });
      req.flush(mockResponse);
    });
  });

  describe('findPayment', () => {
    it('should return payments for an order', () => {
      const mockPaymentsData = [
        {
          id: 1,
          tipo: 'Tarjeta',
          cantidad: 25.00,
          fecha: '2025-05-15T10:00:00.000Z',
          idPedido: 1,
          idCliente: 1,
          razonSocial: 'Cliente Uno',
          CIF: 'C11111111',
          nFactura: 'FAC-001',
          correo: 'cliente1@example.com',
          direccionFiscal: 'Dir Uno',
        },
        {
          id: 2,
          tipo: 'Efectivo',
          cantidad: 25.00,
          fecha: '2025-05-15T10:15:00.000Z',
          idPedido: 1,
          idCliente: 2,
          razonSocial: 'Cliente Dos',
          CIF: 'C22222222',
          nFactura: 'FAC-002',
          correo: 'cliente2@example.com',
          direccionFiscal: 'Dir Dos',
        },
      ];

      service.findPayment(1).subscribe(payments => {
        expect(payments.length).toBe(2);
        expect(payments[0]).toBeInstanceOf(Payment);
        expect(payments[0].tipo).toBe('Tarjeta');
        expect(payments[1].tipo).toBe('Efectivo');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order/payments?id_pedido=1`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockPaymentsData });
    });

    it('should throw error when response is empty', () => {
      service.findPayment(1).subscribe({
        next: () => fail('Expected an error, not payments'),
        error: (error) => {
          expect(error.message).toBe('La respuesta del servidor no contiene pagos válidos');
        }
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order/payments?id_pedido=1`);
      req.flush({ data: [] });
    });

    it('should throw error when response is not an array', () => {
      service.findPayment(1).subscribe({
        next: () => fail('Expected an error, not payments'),
        error: (error) => {
          expect(error.message).toBe('La respuesta del servidor no contiene pagos válidos');
        }
      });

      const req = httpMock.expectOne(`${mockApiUrl}/order/payments?id_pedido=1`);
      req.flush({ data: {} }); // Non-array response
    });
  });

  describe('updateClient', () => {
    it('should send PUT request with client data', () => {
      const mockClient: Client = {
        id: 3,
        razonSocial: 'Cliente Tres Actualizado',
        cif: 'C33333333',
        direccion: 'Dir Tres Actualizada',
        email: 'cliente3.updated@example.com',
      };
      const paymentId = 5;
      const mockResponse: Payment = {
        id: paymentId,
        tipo: 'Tarjeta',
        cantidad: 75.00,
        idPedido: 2,
        idCliente: mockClient.id,
        razonSocial: mockClient.razonSocial,
        cif: mockClient.cif,
        correo: mockClient.email,
        direccionFiscal: mockClient.direccion,
        fecha: undefined,
        nFactura: undefined
      };

      service.updateClient(mockClient, paymentId).subscribe(payment => {
        expect(payment).toEqual(jasmine.objectContaining({
          id: paymentId,
          idCliente: mockClient.id,
          razonSocial: mockClient.razonSocial,
          cif: mockClient.cif,
          correo: mockClient.email,
          direccionFiscal: mockClient.direccion,
        }));
      });

      const req = httpMock.expectOne(`${mockApiUrl}/payment`);
      expect(req.request.method).toBe('PUT');
      expect(req.request.body).toEqual({
        id: paymentId,
        idCliente: mockClient.id,
        razonSocial: mockClient.razonSocial,
        CIF: mockClient.cif,
        direccionFiscal: mockClient.direccion,
        correo: mockClient.email,
      });
      req.flush(mockResponse);
    });
  });
});