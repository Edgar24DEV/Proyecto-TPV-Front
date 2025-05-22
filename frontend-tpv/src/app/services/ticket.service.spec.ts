import { TestBed } from '@angular/core/testing';
import { TicketService } from './ticket.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { environment } from 'src/environments/environment';

describe('TicketService', () => {
  let service: TicketService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        TicketService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(TicketService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should generate ticket with correct parameters', () => {
    const mockResponse = { ticket_url: 'http://example.com/ticket/123' };
    const idPedido = 1;
    const total = 100;
    const iva = 21;
    const idRestaurante = 5;

    service.generateTicket(idPedido, total, iva, idRestaurante).subscribe(response => {
      expect(response).toEqual(mockResponse);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/generate-ticket`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({
      id_pedido: idPedido,
      id_restaurante: idRestaurante,
      total,
      iva
    });

    req.flush(mockResponse);
  });

  it('should generate bill with correct parameters', () => {
    const mockResponse = { ticket_url: 'http://example.com/bill/456' };
    const idPedido = 2;
    const total = 150;
    const iva = 21;
    const idRestaurante = 5;
    const idCliente = 10;
    const tipo = 'factura';

    service.generateBill(idPedido, total, iva, idRestaurante, idCliente, tipo).subscribe(response => {
      expect(response).toEqual(mockResponse);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/generate-bill`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({
      id_pedido: idPedido,
      id_restaurante: idRestaurante,
      id_cliente: idCliente,
      total,
      iva,
      tipo
    });

    req.flush(mockResponse);
  });

  it('should update bill with correct parameters', () => {
    const mockResponse = { ticket_url: 'http://example.com/bill/789' };
    const idPedido = 3;
    const total = 200;
    const iva = 21;
    const idRestaurante = 5;
    const idCliente = 10;
    const tipo = 'factura';

    service.updateBill(idPedido, total, iva, idRestaurante, idCliente, tipo).subscribe(response => {
      expect(response).toEqual(mockResponse);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/update-bill`);
    expect(req.request.method).toBe('PUT');
    expect(req.request.body).toEqual({
      id_pedido: idPedido,
      id_restaurante: idRestaurante,
      id_cliente: idCliente,
      total,
      iva,
      tipo
    });

    req.flush(mockResponse);
  });

  it('should get bill with correct parameters', () => {
    const mockResponse = { url: 'http://example.com/bill/101' };
    const nFactura = 'FAC-2023-101';

    service.getBill(nFactura).subscribe(response => {
      expect(response).toEqual(mockResponse);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/show-bill?n_factura=${nFactura}`);
    expect(req.request.method).toBe('GET');
    expect(req.request.params.get('n_factura')).toBe(nFactura);

    req.flush(mockResponse);
  });
});