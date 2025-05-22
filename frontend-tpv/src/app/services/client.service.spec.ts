import { TestBed } from '@angular/core/testing';
import { ClientService } from './client.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Client } from '../models/Client';
import { environment } from 'src/environments/environment';

describe('ClientService', () => {
  let service: ClientService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        ClientService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(ClientService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  describe('getListClientsCompany', () => {
    it('should get clients by company id', () => {
      const mockClients = [
        { id: 1, razon_social: 'Empresa A', cif: 'CIF123', direccion_fiscal: 'Calle A', email: 'empresaA@example.com' },
        { id: 2, razon_social: 'Empresa B', cif: 'CIF456', direccion_fiscal: 'Calle B', email: 'empresaB@example.com' }
      ];

      service.getListClientsCompany(1).subscribe(clients => {
        expect(clients.length).toBe(2);
        expect(clients[0]).toBeInstanceOf(Client);
        expect(clients[0].razonSocial).toBe('Empresa A');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/clients?id_empresa=1`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockClients });
    });

    it('should throw error when response is not an array', () => {
      service.getListClientsCompany(1).subscribe({
        error: (err) => {
          expect(err.message).toBe('La respuesta del servidor no es una lista');
        }
      });

      const req = httpMock.expectOne(`${mockApiUrl}/clients?id_empresa=1`);
      req.flush({ data: {} }); // Non-array response
    });
  });

  describe('findClientByCif', () => {
    it('should get client by CIF', () => {
      const mockClient = { id: 1, razon_social: 'Empresa A', cif: 'CIF123', direccion_fiscal: 'Calle A', email: 'empresaA@example.com' };

      service.findClientByCif('CIF123').subscribe(client => {
        expect(client).toBeInstanceOf(Client);
        expect(client?.razonSocial).toBe('Empresa A');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/clients/find-by-cif?cif=CIF123`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockClient });
    });

    it('should return null when client does not exist', () => {
      service.findClientByCif('CIF999').subscribe(client => {
        expect(client).toBeNull();
      });

      const req = httpMock.expectOne(`${mockApiUrl}/clients/find-by-cif?cif=CIF999`);
      expect(req.request.method).toBe('GET');
      req.flush({});
    });
  });

  describe('addClient', () => {
    it('should create a new client', () => {
      const newClient = new Client({ razonSocial: 'Empresa Nueva', cif: 'CIFNEW', direccion: 'Calle Nueva', email: 'empresaNew@example.com' });

      service.addClient(newClient, 1).subscribe(client => {
        expect(client).toEqual(newClient);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/clients`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({
        razon_social: 'Empresa Nueva',
        cif: 'CIFNEW',
        direccion_fiscal: 'Calle Nueva',
        correo: 'empresaNew@example.com',
        id_empresa: 1
      });

      req.flush(newClient);
    });
  });

  describe('updateClient', () => {
    it('should update an existing client', () => {
      const updatedClient = new Client({ id: 1, razonSocial: 'Empresa Modificada', cif: 'CIFMOD', direccion: 'Calle Modificada', email: 'empresaMod@example.com' });

      service.updateClient(updatedClient, 1).subscribe(client => {
        expect(client).toEqual(updatedClient);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/clients`);
      expect(req.request.method).toBe('PUT');
      expect(req.request.body).toEqual({
        id: 1,
        razon_social: 'Empresa Modificada',
        cif: 'CIFMOD',
        direccion_fiscal: 'Calle Modificada',
        correo: 'empresaMod@example.com',
        id_empresa: 1
      });

      req.flush(updatedClient);
    });
  });

  describe('deleteClient', () => {
    it('should delete a client', () => {
      const clientId = 1;

      service.deleteClient(clientId).subscribe(response => {
        expect(response).toBeTruthy();
      });

      const req = httpMock.expectOne(`${mockApiUrl}/clients?id=1`);
      expect(req.request.method).toBe('DELETE');
      req.flush({ success: true });
    });
  });
});
