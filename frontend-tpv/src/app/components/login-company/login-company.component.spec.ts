import { TestBed, ComponentFixture, waitForAsync } from '@angular/core/testing';
import { LoginCompanyComponent } from './login-company.component';
import { CompanyService } from 'src/app/services/company.service';
import { Router } from '@angular/router';
import { AppComponent } from 'src/app/app.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { of, throwError } from 'rxjs';
import { Company } from 'src/app/models/Company';

describe('LoginCompanyComponent', () => {
  let component: LoginCompanyComponent;
  let fixture: ComponentFixture<LoginCompanyComponent>;
  let mockCompanyService: jasmine.SpyObj<CompanyService>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockAppComponent: jasmine.SpyObj<AppComponent>;

  beforeEach(waitForAsync(() => {
    mockCompanyService = jasmine.createSpyObj('CompanyService', ['postLoginCompany']);
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockAppComponent = jasmine.createSpyObj('AppComponent', ['reloadHeader']);

    TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), FormsModule, ReactiveFormsModule, LoginCompanyComponent], // ✅ Se usa `imports` en lugar de `declarations`
      providers: [
        { provide: CompanyService, useValue: mockCompanyService },
        { provide: Router, useValue: mockRouter },
        { provide: AppComponent, useValue: mockAppComponent }
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(LoginCompanyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  describe('ngOnInit', () => {
    it('should clear localStorage and reload header', () => {
      spyOn(localStorage, 'clear');

      component.ngOnInit();

      expect(localStorage.clear).toHaveBeenCalled();
      expect(mockAppComponent.reloadHeader).toHaveBeenCalled();
    });
  });

  describe('loginCompany', () => {
    it('should successfully login and navigate to restaurants', () => {
      component.loginCompanyForm.setValue({ nombre: 'TestCompany', contrasenya: '1234' });

      mockCompanyService.postLoginCompany.and.returnValue(of({
        id: 1,
        nombre: 'TestCompany',
        razonSocial: 'Empresa Test'
      } as Company));

      component.loginCompany();

      expect(mockCompanyService.postLoginCompany).toHaveBeenCalledWith('TestCompany', '1234');
      expect(localStorage.getItem('company')).toBe('TestCompany');
      expect(localStorage.getItem('razonSocial')).toBe('Empresa Test');
      expect(localStorage.getItem('idCompany')).toBe('1');
      expect(mockAppComponent.reloadHeader).toHaveBeenCalled();
      expect(mockRouter.navigate).toHaveBeenCalledWith(['restaurants']);
    });

    it('should show error message if login fails', () => {
      component.loginCompanyForm.setValue({ nombre: 'TestCompany', contrasenya: '1234' });

      mockCompanyService.postLoginCompany.and.returnValue(throwError(() => new Error('Login failed')));

      component.loginCompany();

      expect(component.mensajeError).toBeTrue();
    });

    it('should do nothing if form is invalid', () => {
      spyOn(console, 'log');

      component.loginCompanyForm.setValue({ nombre: '', contrasenya: '' });
      component.loginCompany();

      expect(console.log).toHaveBeenCalledWith('Formulario no válido');
      expect(mockCompanyService.postLoginCompany).not.toHaveBeenCalled();
    });
  });

  describe('volver', () => {
    it('should navigate to select-login', () => {
      component.volver();

      expect(mockRouter.navigate).toHaveBeenCalledWith(['select-login']);
    });
  });
});
