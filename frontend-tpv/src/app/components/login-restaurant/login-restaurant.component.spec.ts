import { TestBed, ComponentFixture, waitForAsync } from '@angular/core/testing';
import { LoginRestaurantComponent } from './login-restaurant.component';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { Router } from '@angular/router';
import { AppComponent } from 'src/app/app.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { of, throwError } from 'rxjs';
import { Restaurant } from 'src/app/models/Restaurant';

describe('LoginRestaurantComponent', () => {
  let component: LoginRestaurantComponent;
  let fixture: ComponentFixture<LoginRestaurantComponent>;
  let mockRestaurantService: jasmine.SpyObj<RestaurantService>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockAppComponent: jasmine.SpyObj<AppComponent>;

  beforeEach(waitForAsync(() => {
    mockRestaurantService = jasmine.createSpyObj('RestaurantService', ['postLoginRestaurant']);
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockAppComponent = jasmine.createSpyObj('AppComponent', ['reloadHeader']);

    TestBed.configureTestingModule({
      imports: [IonicModule.forRoot(), FormsModule, ReactiveFormsModule, LoginRestaurantComponent], // ✅ `imports` en lugar de `declarations`
      providers: [
        { provide: RestaurantService, useValue: mockRestaurantService },
        { provide: Router, useValue: mockRouter },
        { provide: AppComponent, useValue: mockAppComponent }
      ],
    }).compileComponents();

    fixture = TestBed.createComponent(LoginRestaurantComponent);
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

  describe('loginRestaurant', () => {
    it('should successfully login and navigate to employees', () => {
      component.loginRestaurantForm.setValue({ nombre: 'TestRestaurant', contrasenya: '1234' });

      mockRestaurantService.postLoginRestaurant.and.returnValue(of({
        id: 1,
        nombre: 'TestRestaurant'
      } as Restaurant));

      component.loginRestaurant();

      expect(mockRestaurantService.postLoginRestaurant).toHaveBeenCalledWith('TestRestaurant', '1234');
      expect(localStorage.getItem('restaurant')).toBe('TestRestaurant');
      expect(localStorage.getItem('idRestaurant')).toBe('1');
      expect(mockAppComponent.reloadHeader).toHaveBeenCalled();
      expect(mockRouter.navigate).toHaveBeenCalledWith(['/employees']);
    });

    it('should show error message if login fails', () => {
      component.loginRestaurantForm.setValue({ nombre: 'TestRestaurant', contrasenya: '1234' });

      mockRestaurantService.postLoginRestaurant.and.returnValue(throwError(() => new Error('Login failed')));

      component.loginRestaurant();

      expect(component.mensajeError).toBeTrue();
    });

    it('should do nothing if form is invalid', () => {
      spyOn(console, 'log');

      component.loginRestaurantForm.setValue({ nombre: '', contrasenya: '' });
      component.loginRestaurant();

      expect(console.log).toHaveBeenCalledWith('Formulario no válido');
      expect(mockRestaurantService.postLoginRestaurant).not.toHaveBeenCalled();
    });
  });

  describe('volver', () => {
    it('should navigate to select-login', () => {
      component.volver();

      expect(mockRouter.navigate).toHaveBeenCalledWith(['select-login']);
    });
  });
});
