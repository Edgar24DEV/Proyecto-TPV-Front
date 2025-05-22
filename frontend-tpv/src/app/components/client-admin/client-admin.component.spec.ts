import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ClientAdminComponent } from './client-admin.component';
import { Router } from '@angular/router';
import { ClientService } from 'src/app/services/client.service';
import { AlertService } from 'src/app/services/alert.service';
import { of } from 'rxjs';
import { ReactiveFormsModule } from '@angular/forms';

describe('ClientAdminComponent', () => {
  let component: ClientAdminComponent;
  let fixture: ComponentFixture<ClientAdminComponent>;
  let routerSpy: jasmine.SpyObj<Router>;
  let clientServiceSpy: jasmine.SpyObj<ClientService>;

  beforeEach(async () => {
    routerSpy = jasmine.createSpyObj('Router', ['navigate']);
    clientServiceSpy = jasmine.createSpyObj('ClientService', ['getListClientsCompany']);

    await TestBed.configureTestingModule({
      imports: [ReactiveFormsModule, ClientAdminComponent],
      providers: [
        { provide: Router, useValue: routerSpy },
        { provide: ClientService, useValue: clientServiceSpy },
        { provide: AlertService, useValue: jasmine.createSpyObj('AlertService', ['show']) },
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(ClientAdminComponent);
    component = fixture.componentInstance;
  });

  afterEach(() => {
    localStorage.clear();
  });

  it('debería redirigir a employees si faltan idCompany e idRestaurant', () => {
    localStorage.removeItem('idCompany');
    localStorage.removeItem('idRestaurant');
    localStorage.setItem('employeeRol', JSON.stringify({ clientes: false }));

    component.ngOnInit();

    expect(routerSpy.navigate).toHaveBeenCalledWith(['employees/panel']);
  });



  it('debería continuar si están todos los valores correctos', () => {
    localStorage.setItem('idCompany', '1');
    localStorage.setItem('idRestaurant', '2');
    localStorage.setItem('employeeRol', JSON.stringify({ clientes: true, idEmpresa: 1 }));
    clientServiceSpy.getListClientsCompany.and.returnValue(of([]));

    component.ngOnInit();

    expect(routerSpy.navigate).not.toHaveBeenCalled();
    expect(clientServiceSpy.getListClientsCompany).toHaveBeenCalledWith(1);
  });
});

