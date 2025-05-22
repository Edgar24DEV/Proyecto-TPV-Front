import { TestBed } from '@angular/core/testing';
import { LocationService } from './location.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Location } from '../models/Location';
import { environment } from 'src/environments/environment';

describe('LocationService', () => {
  let service: LocationService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        LocationService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(LocationService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  describe('getListLocationRestaurant', () => {
    it('should get locations by restaurant id', () => {
      const mockLocations = [
        {
          id: 1,
          ubicacion: 'Terraza',
          activo: true,
          idRestaurante: 5
        },
        {
          id: 2,
          ubicacion: 'Interior',
          activo: true,
          idRestaurante: 5
        }
      ];

      service.getListLocationRestaurant(5).subscribe(locations => {
        expect(locations.length).toBe(2);
        expect(locations[0]).toBeInstanceOf(Location);
        expect(locations[0].ubicacion).toBe('Terraza');
        expect(locations[1].activo).toBe(undefined);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/locations?id_restaurante=5`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockLocations });
    });

    it('should throw error when response is not an array', () => {
      service.getListLocationRestaurant(5).subscribe({
        error: (err) => {
          expect(err.message).toBe('La respuesta del servidor no es una lista');
        }
      });

      const req = httpMock.expectOne(`${mockApiUrl}/locations?id_restaurante=5`);
      req.flush({ data: {} }); // Non-array response
    });
  });

  describe('findByIdLocation', () => {
    it('should get location by id', () => {
      const mockLocation = {
        id: 1,
        ubicacion: 'Barra',
        activoStatus: true,
        idRestaurant: 5
      };

      service.findByIdLocation(1).subscribe(location => {
        expect(location).toBeInstanceOf(Location);
        expect(location.id).toBe(1);
        expect(location.ubicacion).toBe('Barra');
        expect(location.activo).toBe(true);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/location/find-by-id?id=1`);
      expect(req.request.method).toBe('GET');
      req.flush({ data: mockLocation });
    });
  });

  describe('addLocation', () => {
    it('should create a new location', () => {
      const newLocation = new Location({
        ubicacion: 'Nueva Zona',
        idRestaurante: 5
      });
      const mockResponse = new Location({
        id: 3,
        ubicacion: 'Nueva Zona',
        activo: true,
        idRestaurante: 5
      });

      service.addLocation(newLocation).subscribe(location => {
        expect(location).toBeInstanceOf(Location);
        expect(location.ubicacion).toBe('Nueva Zona');
      });

      const req = httpMock.expectOne(`${mockApiUrl}/location`);
      expect(req.request.method).toBe('POST');
      expect(req.request.body).toEqual({
        ubicacion: 'Nueva Zona',
        id_restaurante: 5
      });
      req.flush(mockResponse);
    });
  });

  describe('updateLocation', () => {
    it('should update an existing location', () => {
      const updatedLocation = new Location({
        id: 1,
        ubicacion: 'Terraza Actualizada',
        activo: true,
        idRestaurante: 5
      });
      const mockResponse = new Location( {
        id: 1,
        ubicacion: 'Terraza Actualizada',
        activo: true,
        idRestaurante: 5
      });

      service.updateLocation(updatedLocation).subscribe(location => {
        expect(location).toBeInstanceOf(Location);
        expect(location.ubicacion).toBe('Terraza Actualizada');
        expect(location.activo).toBe(true);
      });

      const req = httpMock.expectOne(`${mockApiUrl}/location`);
      expect(req.request.method).toBe('PUT');
      expect(req.request.body).toEqual({
        id: 1,
        ubicacion: 'Terraza Actualizada',
        activo: 1,
        id_restaurante: 5
      });
      req.flush(mockResponse);
    });

    it('should convert activo boolean to number', () => {
      const location = new Location({
        id: 1,
        ubicacion: 'Terraza',
        activo: false,
        idRestaurante: 5
      });

      service.updateLocation(location).subscribe();

      const req = httpMock.expectOne(`${mockApiUrl}/location`);
      expect(req.request.body.activo).toBe(0);
      req.flush({});
    });
  });

  describe('deleteLocation', () => {
    it('should delete a location', () => {
      const locationId = 1;

      service.deleteLocation(locationId).subscribe(response => {
        expect(response).toBeTruthy();
      });

      const req = httpMock.expectOne(`${mockApiUrl}/location?id=1`);
      expect(req.request.method).toBe('DELETE');
      req.flush({ success: true });
    });
  });
});