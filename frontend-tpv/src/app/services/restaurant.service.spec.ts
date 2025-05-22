import { TestBed } from '@angular/core/testing';
import { RestaurantService } from './restaurant.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Restaurant } from '../models/Restaurant';
import { environment } from 'src/environments/environment';

describe('RestaurantService', () => {
  let service: RestaurantService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        RestaurantService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(RestaurantService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should get restaurant by id', () => {
    const mockRestaurant = {
      id: 1,
      razonSocial: 'Restaurante Test',
      direccion: 'Calle Falsa 123',
      telefono: '912345678',
      contrasenya: 'password',
      direccionFiscal: 'Calle Fiscal 456',
      cif: 'A12345678',
      idEmpresa: 1
    };

    service.getRestaurant(1).subscribe(restaurant => {
      expect(restaurant).toBeInstanceOf(Restaurant);
      expect(restaurant.id).toBe(1);
      expect(restaurant.razonSocial).toBe('Restaurante Test');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant?id_restaurante=1`);
    expect(req.request.method).toBe('GET');
    req.flush({ data: mockRestaurant });
  });

  it('should get list of restaurants by company id', () => {
    const mockRestaurants = [
      {
        id: 1,
        nombre: 'Restaurante 1',
        razonSocial: 'Razón Social 1',
        direccion: 'Dirección 1',
        telefono: '911111111',
        contrasenya: 'pass1',
        direccionFiscal: 'Fiscal 1',
        cif: 'A11111111',
        idEmpresa: 1
      },
      {
        id: 2,
        nombre: 'Restaurante 2',
        razonSocial: 'Razón Social 2',
        direccion: 'Dirección 2',
        telefono: '922222222',
        contrasenya: 'pass2',
        direccionFiscal: 'Fiscal 2',
        cif: 'A22222222',
        idEmpresa: 1
      }
    ];

    service.getListRestaurantCompany(1).subscribe(restaurants => {
      expect(restaurants.length).toBe(2);
      expect(restaurants[0]).toBeInstanceOf(Restaurant);
      expect(restaurants[1].nombre).toBe('Restaurante 2');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurants?id_empresa=1`);
    expect(req.request.method).toBe('GET');
    req.flush({ data: mockRestaurants });
  });

  it('should handle error when server response is not an array for getListRestaurantCompany', () => {
    service.getListRestaurantCompany(1).subscribe({
      error: (err) => {
        expect(err.message).toBe('La respuesta del servidor no es una lista');
      }
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurants?id_empresa=1`);
    req.flush({ data: {} }); // Enviamos un objeto en lugar de un array
  });

  it('should login restaurant', () => {
    const mockResponse = {
      id: 1,
      nombre: 'Restaurante Login',
      direccion: 'Calle Login 123',
      telefono: '933333333',
      direccionFiscal: 'Fiscal Login',
      cif: 'A33333333',
      razonSocial: 'Razón Login',
      idEmpresa: 1
    };

    service.postLoginRestaurant('Restaurante Login', 'password').subscribe(restaurant => {
      expect(restaurant).toBeInstanceOf(Restaurant);
      expect(restaurant.nombre).toBe('Restaurante Login');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant/login`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({
      nombre: 'Restaurante Login',
      contrasenya: 'password'
    });
    req.flush({ data: mockResponse });
  });

  it('should handle unexpected server response for login', () => {
    service.postLoginRestaurant('test', 'pass').subscribe({
      error: (err) => {
        expect(err.message).toBe('Respuesta inesperada del servidor');
      }
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant/login`);
    req.flush('unexpected response'); // Respuesta no válida
  });

  it('should create new restaurant', () => {
    const newRestaurant = new Restaurant({
      nombre: 'Nuevo Restaurante',
      direccion: 'Calle Nueva 123',
      telefono: '944444444',
      contrasenya: 'newpass',
      direccionFiscal: 'Fiscal Nueva',
      cif: 'A44444444',
      razonSocial: 'Razón Nueva'
    });

    const idEmpresa = 1;

    service.postRestaurant(newRestaurant, idEmpresa).subscribe(restaurant => {
      expect(restaurant).toEqual(newRestaurant);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({
      nombre: 'Nuevo Restaurante',
      direccion: 'Calle Nueva 123',
      telefono: '944444444',
      contrasenya: 'newpass',
      direccion_fiscal: 'Fiscal Nueva',
      cif: 'A44444444',
      razon_social: 'Razón Nueva',
      id_empresa: 1
    });

    req.flush(newRestaurant);
  });

  it('should update restaurant', () => {
    const updatedRestaurant = new Restaurant({
      id: 1,
      nombre: 'Restaurante Actualizado',
      direccion: 'Calle Actualizada 123',
      telefono: '955555555',
      direccionFiscal: 'Fiscal Actualizada',
      cif: 'A55555555',
      razonSocial: 'Razón Actualizada'
    });

    const idEmpresa = 1;
    const idRestaurante = 1;

    service.putRestaurant(updatedRestaurant, idEmpresa, idRestaurante).subscribe(restaurant => {
      expect(restaurant).toEqual(updatedRestaurant);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant`);
    expect(req.request.method).toBe('PUT');
    expect(req.request.body).toEqual({
      id: 1,
      nombre: 'Restaurante Actualizado',
      direccion: 'Calle Actualizada 123',
      telefono: '955555555',
      direccion_fiscal: 'Fiscal Actualizada',
      cif: 'A55555555',
      razon_social: 'Razón Actualizada',
      id_empresa: 1
    });

    req.flush(updatedRestaurant);
  });

  it('should delete restaurant', () => {
    const restaurantId = 1;

    service.deleteRestaurant(restaurantId).subscribe(response => {
      expect(response).toBeTruthy();
    });

    const req = httpMock.expectOne(`${mockApiUrl}/restaurant`);
    expect(req.request.method).toBe('DELETE');
    expect(req.request.body).toEqual({ id: 1 });
    req.flush({ success: true });
  });
});