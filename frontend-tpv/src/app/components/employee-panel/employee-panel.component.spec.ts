// employee-panel.component.spec.ts

import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';
import { EmployeePanelComponent } from './employee-panel.component';
import { Router } from '@angular/router';
import { AppComponent } from 'src/app/app.component';

describe('EmployeePanelComponent', () => {
  let component: EmployeePanelComponent;
  let fixture: ComponentFixture<EmployeePanelComponent>;
  let routerSpy: jasmine.SpyObj<Router>;
  let appComponentStub: Partial<AppComponent>;

  beforeEach(waitForAsync(() => {
    // 1) Prepara un employeeRol completo en localStorage
    const fakeRol = {
      usuarios: true,
      productos: true,
      mesas: true,
      categorias: true,
      pedidos: true,
      tpv: true,
      clientes: true,
      empresa: true,
      idEmpresa: 123
    };
    localStorage.setItem('employeeRol', JSON.stringify(fakeRol));

    // 2) Spy para Router
    routerSpy = jasmine.createSpyObj('Router', ['navigate']);
    // 3) Stub mínimo para AppComponent (solo reloadHeader)
    appComponentStub = { reloadHeader: jasmine.createSpy('reloadHeader') };

    TestBed.configureTestingModule({
      imports: [
        IonicModule.forRoot(),
        EmployeePanelComponent // importamos el standalone component
      ],
      providers: [
        { provide: Router, useValue: routerSpy },
        { provide: AppComponent, useValue: appComponentStub }
      ]
    })
    .compileComponents()
    .then(() => {
      fixture = TestBed.createComponent(EmployeePanelComponent);
      component = fixture.componentInstance;
      fixture.detectChanges(); // ya no dará undefined
    });
  }));

  it('should create the component and call reloadHeader()', () => {
    expect(component).toBeTruthy();
    expect(appComponentStub.reloadHeader).toHaveBeenCalled();
  });

  it('tpv() should navigate to "restaurant/tables"', () => {
    component.tpv();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['restaurant/tables']);
  });

  it('order() should navigate to "order-admin"', () => {
    component.order();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['order-admin']);
  });

  it('table() should navigate to "tables-admin"', () => {
    component.table();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['tables-admin']);
  });

  it('products() should navigate to "product-admin"', () => {
    component.products();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['product-admin']);
  });

  it('users() should navigate to "user-admin"', () => {
    component.users();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['user-admin']);
  });

  it('clients() should navigate to "client-admin"', () => {
    component.clients();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['client-admin']);
  });

  it('category() should navigate to "category-admin"', () => {
    component.category();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['category-admin']);
  });

  it('navigate("foo") should navigate to provided route', () => {
    component.navigate('foo');
    expect(routerSpy.navigate).toHaveBeenCalledWith(['foo']);
  });
});
