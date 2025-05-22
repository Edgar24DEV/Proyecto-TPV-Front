import { TestBed } from '@angular/core/testing';
import { RoleService } from './role.service';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting, HttpTestingController } from '@angular/common/http/testing';
import { Role } from '../models/Role';
import { environment } from 'src/environments/environment';

describe('RoleService', () => {
  let service: RoleService;
  let httpMock: HttpTestingController;
  const mockApiUrl = environment.apiUrl;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        RoleService,
        provideHttpClient(),
        provideHttpClientTesting(),
      ]
    });

    service = TestBed.inject(RoleService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should get role by id', () => {
    const mockRole = {
      id: 1,
      rol: 'Admin',
      productos: 1,
      categorias: 1,
      tpv: 1,
      usuarios: 1,
      mesas: 1,
      restaurante: 1,
      clientes: 1,
      empresa: 1,
      pago: 1,
      id_empresa: 1
    };

    service.getRole(1).subscribe(role => {
      expect(role).toBeInstanceOf(Role);
      expect(role.id).toBe(1);
      expect(role.rol).toBe('Admin');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/role?id=1`);
    expect(req.request.method).toBe('GET');
    req.flush({ data: mockRole });
  });

  it('should get roles by company id', () => {
    const mockRoles = [
      {
        id: 1,
        rol: 'Manager',
        productos: 1,
        categorias: 0,
        tpv: 1,
        usuarios: 0,
        mesas: 1,
        restaurante: 1,
        clientes: 0,
        empresa: 0,
        pago: 1,
        id_empresa: 1
      },
      {
        id: 2,
        rol: 'Waiter',
        productos: 0,
        categorias: 0,
        tpv: 1,
        usuarios: 0,
        mesas: 1,
        restaurante: 0,
        clientes: 1,
        empresa: 0,
        pago: 0,
        id_empresa: 1
      }
    ];

    service.getRolesCompany(1).subscribe(roles => {
      expect(roles.length).toBe(2);
      expect(roles[0]).toBeInstanceOf(Role);
      expect(roles[1].rol).toBe('Waiter');
    });

    const req = httpMock.expectOne(`${mockApiUrl}/roles?id_empresa=1`);
    expect(req.request.method).toBe('GET');
    req.flush({ data: mockRoles });
  });

  it('should add new role', () => {
    const newRole = new Role({
      rol: 'Test Role',
      productos: true,
      categorias: false,
      tpv: true,
      usuarios: false,
      mesas: true,
      restaurante: false,
      clientes: true,
      empresa: false,
      pago: true
    });

    const idCompany = 1;

    service.addRole(newRole, idCompany).subscribe(role => {
      expect(role).toEqual(newRole);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/role`);
    expect(req.request.method).toBe('POST');
    expect(req.request.body).toEqual({
      rol: 'Test Role',
      productos: 1,
      categorias: 0,
      tpv: 1,
      usuarios: 0,
      mesas: 1,
      restaurante: 0,
      clientes: 1,
      empresa: 0,
      pago: 1,
      id_empresa: 1
    });

    req.flush(newRole);
  });

  it('should update role', () => {
    const updatedRole = new Role({
      id: 1,
      rol: 'Updated Role',
      productos: false,
      categorias: true,
      tpv: false,
      usuarios: true,
      mesas: false,
      restaurante: true,
      clientes: false,
      empresa: true,
      pago: false
    });

    const idCompany = 1;

    service.updateRole(updatedRole, idCompany).subscribe(role => {
      expect(role).toEqual(updatedRole);
    });

    const req = httpMock.expectOne(`${mockApiUrl}/role`);
    expect(req.request.method).toBe('PUT');
    expect(req.request.body).toEqual({
      id: 1,
      rol: 'Updated Role',
      productos: 0,
      categorias: 1,
      tpv: 0,
      usuarios: 1,
      mesas: 0,
      restaurante: 1,
      clientes: 0,
      empresa: 1,
      pago: 0,
      id_empresa: 1
    });

    req.flush(updatedRole);
  });

  it('should delete role', () => {
    const roleId = 1;

    service.deleteRole(roleId).subscribe(response => {
      expect(response).toBeTruthy();
    });

    const req = httpMock.expectOne(`${mockApiUrl}/role?id=1`);
    expect(req.request.method).toBe('DELETE');
    req.flush({ success: true });
  });

  it('should handle error when server response is not an array for getRolesCompany', () => {
    service.getRolesCompany(1).subscribe({
      error: (err) => {
        expect(err.message).toBe('La respuesta del servidor no es una lista');
      }
    });

    const req = httpMock.expectOne(`${mockApiUrl}/roles?id_empresa=1`);
    req.flush({ data: {} }); // Enviamos un objeto en lugar de un array
  });
});