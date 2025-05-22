import { TestBed } from '@angular/core/testing';
import { CompanyService } from './company.service';
import { provideHttpClient } from '@angular/common/http';
import {
  provideHttpClientTesting,
  HttpTestingController,
} from '@angular/common/http/testing';
import { Company } from '../models/Company';
import { environment } from 'src/environments/environment';

describe('CompanyService', () => {
  let service: CompanyService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        CompanyService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ],
    });

    service = TestBed.inject(CompanyService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  describe('postLoginCompany', () => {
    it('should authenticate company', () => {
      const mockCompany = {
        id: 1,
        nombre: 'Empresa X',
        direccionFiscal: 'Calle Fiscal 123',
        cif: 'ABC123',
        razonSocial: 'Empresa X S.A.',
        telefono: '123456789',
        correo: 'empresa@example.com',
        contrasenya: 'securepass',
      };

      service
        .postLoginCompany('Empresa X', 'securepass')
        .subscribe((company) => {
          expect(company).toBeInstanceOf(Company);
          expect(company.nombre).toBe('Empresa X');
        });

      const req = httpMock.expectOne(`${mockApiUrl}/company/login`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({
        nombre: 'Empresa X',
        contrasenya: 'securepass',
      });

      req.flush({ data: mockCompany });
    });

    it('should handle unexpected server response', () => {
      service.postLoginCompany('Empresa X', 'securepass').subscribe({
        error: (err) => {
          expect(err.message).toBe('Respuesta inesperada del servidor');
        },
      });

      const req = httpMock.expectOne(`${mockApiUrl}/company/login`);
      req.flush(null); // Envía `null` en lugar de un objeto vacío
    });
  });
});
