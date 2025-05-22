import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RoleAdminComponent } from './role-admin.component';
import { Router } from '@angular/router';
import { RoleService } from 'src/app/services/role.service';
import { AlertService } from 'src/app/services/alert.service';
import { of } from 'rxjs';
import { FormBuilder, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

describe('RoleAdminComponent', () => {
  let component: RoleAdminComponent;
  let fixture: ComponentFixture<RoleAdminComponent>;
  let routerSpy: jasmine.SpyObj<Router>;
  let roleServiceSpy: jasmine.SpyObj<RoleService>;
  let alertServiceSpy: jasmine.SpyObj<AlertService>;

  beforeEach(async () => {
    routerSpy = jasmine.createSpyObj('Router', ['navigate']);
    roleServiceSpy = jasmine.createSpyObj('RoleService', [
      'getRolesCompany',
      'getRole',
      'addRole',
      'updateRole',
      'deleteRole',
    ]);
    alertServiceSpy = jasmine.createSpyObj('AlertService', ['show']);

    await TestBed.configureTestingModule({
      imports: [ReactiveFormsModule, CommonModule, RoleAdminComponent],
      providers: [
        { provide: Router, useValue: routerSpy },
        { provide: RoleService, useValue: roleServiceSpy },
        { provide: AlertService, useValue: alertServiceSpy },
      ],
    }).compileComponents();
    roleServiceSpy.getRolesCompany.and.returnValue(of([])); 

    fixture = TestBed.createComponent(RoleAdminComponent);
    component = fixture.componentInstance;
  });

  afterEach(() => {
    localStorage.clear();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should load roles on init', () => {
    localStorage.setItem('idCompany', '1');
    roleServiceSpy.getRolesCompany.and.returnValue(of([]));

    component.ngOnInit();
    expect(roleServiceSpy.getRolesCompany).toHaveBeenCalledWith(1);
  });

  

  it('should update an existing role', () => {
    localStorage.setItem('idCompany', '1');
    roleServiceSpy.updateRole.and.returnValue(of());

    component.createRole = false;
    component.role = { id: 1, rol: 'Manager' } as any;
    component.idCompany = 1;

    const mockFormBuilder = TestBed.inject(FormBuilder);
    component.roleForm = mockFormBuilder.group({
      rol: [''],
      productos: false,
      categorias: false,
      tpv: false,
      usuarios: false,
      mesas: false,
      restaurante: false,
      clientes: false,
      empresa: false,
      pago: false,
    });

    component.roleForm.patchValue({ rol: 'Admin' });

    component.saveRole();

    expect(roleServiceSpy.updateRole).toHaveBeenCalled();
  });

  it('should delete a role', () => {
    component.role = { id: 1, rol: 'Manager' } as any;
    roleServiceSpy.deleteRole.and.returnValue(of({}));

    component.delete();

    expect(roleServiceSpy.deleteRole).toHaveBeenCalledWith(1);
  });
});
