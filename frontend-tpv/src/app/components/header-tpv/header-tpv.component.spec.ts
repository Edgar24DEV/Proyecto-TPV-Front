import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { HeaderTpvComponent } from './header-tpv.component';
import { Router } from '@angular/router';
import { MenuController } from '@ionic/angular';
import { ChangeDetectorRef } from '@angular/core';
import { AppComponent } from 'src/app/app.component';
import { EmployeeRol } from 'src/app/models/Employee-rol';

describe('HeaderTpvComponent', () => {
  let component: HeaderTpvComponent;
  let fixture: ComponentFixture<HeaderTpvComponent>;
  let routerSpy: jasmine.SpyObj<Router>;
  let menuSpy: jasmine.SpyObj<MenuController>;
  let cdrSpy: jasmine.SpyObj<ChangeDetectorRef>;
  let appComponentSpy: jasmine.SpyObj<AppComponent>;

  beforeEach(waitForAsync(() => {
    routerSpy = jasmine.createSpyObj('Router', ['navigate']);
    menuSpy = jasmine.createSpyObj('MenuController', ['close']);
    cdrSpy = jasmine.createSpyObj('ChangeDetectorRef', ['detectChanges']);
    appComponentSpy = jasmine.createSpyObj('AppComponent', ['closeAppMenu']);

    TestBed.configureTestingModule({
      imports: [HeaderTpvComponent],
      providers: [
        { provide: Router, useValue: routerSpy },
        { provide: MenuController, useValue: menuSpy },
        { provide: ChangeDetectorRef, useValue: cdrSpy },
        { provide: AppComponent, useValue: appComponentSpy },
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(HeaderTpvComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  it('should navigate to empty route on logoutApp', async () => {
    await component.logoutApp();
    expect(appComponentSpy.closeAppMenu).toHaveBeenCalled();
    expect(localStorage.getItem('anyKey')).toBeNull();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['']);
  });

  it('should navigate to employees route on logoutEmployee', () => {
    localStorage.setItem('employeeRol', 'test');
    component.logoutEmployee();
    expect(menuSpy.close).toHaveBeenCalledWith('first');
    expect(localStorage.getItem('employeeRol')).toBeNull();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['employees']);
  });

  it('should navigate to category-admin on category()', async () => {
    await component.category();
    expect(appComponentSpy.closeAppMenu).toHaveBeenCalled();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['category-admin']);
  });

  it('should navigate to roles-admin on roles()', async () => {
    await component.roles();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['roles-admin']);
  });

  it('should call reloadHeader and navigate on logoutRestaurant()', async () => {
    const reloadSpy = jasmine.createSpy('reload');
    component.reloadHeader = reloadSpy;
    await component.logoutRestaurant();
    expect(localStorage.getItem('anyKey')).toBeNull();
    expect(reloadSpy).toHaveBeenCalled();
    expect(routerSpy.navigate).toHaveBeenCalledWith(['']);
  });

  it('should update isCompanyLoggedIn on ngOnChanges', () => {
    component.ngOnChanges({
      idCompany: {
        currentValue: 1,
        previousValue: 0,
        firstChange: true,
        isFirstChange: () => true
      }
    });
    expect(component.isCompanyLoggedIn).toBeTrue();
  });

  it('should update isCompanyLoggedIn on checkCompanyLogin', () => {
    component.idCompany = 123;
    component.checkCompanyLogin();
    expect(component.isCompanyLoggedIn).toBeTrue();
  });
});
