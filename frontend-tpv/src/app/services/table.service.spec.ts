import { TestBed } from '@angular/core/testing';
import { TableService } from './table.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Table } from '../models/Table';
import { environment } from 'src/environments/environment';

describe('TableService', () => {
  let service: TableService;
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        TableService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(TableService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should return a list of tables', () => {
    const dummyTables = [
      {
        id: 1,
        mesa: 'Mesa 1',
        activo: true,
        idUbicacion: 2,
        posX: 10,
        posY: 20,
      },
    ];

    service.getListTableRestaurant(5).subscribe((tables) => {
      expect(tables.length).toBe(1);
      expect(tables[0]).toBeInstanceOf(Table);
      expect(tables[0].mesa).toBe('Mesa 1');
    });

    const req = httpMock.expectOne(`${environment.apiUrl}/tables?id_restaurante=5`);
    expect(req.request.method).toBe('GET');
    req.flush(dummyTables);
  });

  it('should return a table by id', () => {
    const dummyTable = {
      id: 1,
      mesa: 'Mesa 1',
      activo: true,
      idUbicacion: 2,
      posX: 10,
      posY: 20,
    };

    service.findByIdTable(1).subscribe((table) => {
      expect(table).toBeInstanceOf(Table);
      expect(table.id).toBe(1);
    });

    const req = httpMock.expectOne(`${environment.apiUrl}/table/find-by-id?id=1`);
    expect(req.request.method).toBe('GET');
    req.flush(dummyTable);
  });

  it('should send POST to add a table', () => {
    const newTable = new Table({ mesa: 'Mesa nueva', idUbicacion: 1, pos_x: 5, pos_y: 10 });
    
    service.addTable(newTable).subscribe((res) => {
      expect(res).toEqual(newTable);
    });

    const req = httpMock.expectOne(`${environment.apiUrl}/tables`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({
      mesa: 'Mesa nueva',
      id_ubicacion: 1,
      pos_x: 5,
      pos_y: 10,
    });

    req.flush(newTable);
  });

  it('should send PUT to update a table', () => {
    const updatedTable = new Table({ 
      id: 1, 
      mesa: 'Mesa editada', 
      activo: true, 
      idUbicacion: 1, 
      pos_x: 5, 
      pos_y: 10 
    });

    service.updateTable(updatedTable).subscribe((res) => {
      expect(res).toEqual(updatedTable);
    });

    const req = httpMock.expectOne(`${environment.apiUrl}/tables`);
    expect(req.request.method).toBe('PUT');
    expect(req.request.body).toEqual({
      id: 1,
      mesa: 'Mesa editada',
      activo: 1, // Convertido a número (1 = true, 0 = false)
      id_ubicacion: 1,
      pos_x: 5,
      pos_y: 10,
    });

    req.flush(updatedTable);
  });

  it('should send PUT to update table location', () => {
    const table = new Table({ id: 1, pos_x: 100, pos_y: 200 });

    service.updateTableLocation(table).subscribe((res) => {
      expect(res).toEqual(table);
    });

    const req = httpMock.expectOne(`${environment.apiUrl}/tables/update-location`);
    expect(req.request.method).toBe('PUT');
    expect(req.request.body).toEqual({
      id: 1,
      pos_x: 100,
      pos_y: 200,
    });

    req.flush(table);
  });

  it('should send DELETE to delete table', () => {
    const tableId = 42;

    service.deleteTable(tableId).subscribe((res) => {
      expect(res).toBeTruthy();
    });

    const req = httpMock.expectOne(`${environment.apiUrl}/tables?id=42`);
    expect(req.request.method).toBe('DELETE');
    req.flush({ success: true });
  });
});